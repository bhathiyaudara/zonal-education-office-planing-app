<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'schoolId',
        'buildingName',
        'buildingType',
        'size',
        'lengthFeet',
        'widthFeet',
        'areaSqft',
        'floors',
        'constructionYear',
        'lastRepairYear',
        'status',
        'notes',
    ];

    protected $casts = [
        'lengthFeet' => 'float',
        'widthFeet' => 'float',
        'areaSqft' => 'float',
        'floors' => 'integer',
        'constructionYear' => 'integer',
        'lastRepairYear' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }

    public function getYearsSinceRepairAttribute(): ?int
    {
        if (!$this->lastRepairYear) {
            return null;
        }
        return max(0, (int)date('Y') - $this->lastRepairYear);
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->constructionYear) {
            return null;
        }
        return max(0, (int)date('Y') - $this->constructionYear);
    }

    public function getNeedsRenovationAttribute(): bool
    {
        if (!$this->lastRepairYear) {
            return true;
        }
        return ((int)date('Y') - $this->lastRepairYear) >= 10;
    }

    public function getBilingualStatusAttribute(): string
    {
        $s = trim($this->status ?? '');
        if (!$s) {
            return 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)';
        }

        // 1. Condemned / Unusable
        if (stripos($s, 'Condemn') !== false || stripos($s, 'පරිහරණයට නොගනී') !== false || stripos($s, 'ඉවත් කළ යුතු') !== false) {
            return 'Condemned / Unusable (භාවිතයෙන් ඉවත් කළ යුතු)';
        }

        // 2. Dilapidated / Dangerous
        if (stripos($s, 'Dangerous') !== false || stripos($s, 'Dilapidated') !== false ||
            stripos($s, 'ඉතා අබලන්') !== false || stripos($s, 'අයහපත්') !== false || 
            stripos($s, 'අබලන් තත්ත්වයේ') !== false) {
            return 'Dilapidated / Dangerous (ඉතා අබලන් / අවදානම්)';
        }

        // 3. Major repairs / Renovation needed
        if (stripos($s, 'Major') !== false || stripos($s, 'ප්‍රතිසංස්කරණ') !== false || 
            stripos($s, 'අලුත්වැඩියා') !== false || stripos($s, 'අලුත් වැඩියා') !== false ||
            stripos($s, 'වහල') !== false || 
            stripos($s, 'දොර') !== false || stripos($s, 'ජනෙල්') !== false || stripos($s, 'ජනේල') !== false ||
            stripos($s, 'කපරාරු') !== false || stripos($s, 'සිවිලිම') !== false ||
            stripos($s, 'පොළව') !== false || stripos($s, 'බිම') !== false || stripos($s, 'ගෙබිම') !== false ||
            stripos($s, 'විදුලිය නොමැත') !== false || stripos($s, 'පේමන්ට්') !== false ||
            stripos($s, 'පිළිසකර') !== false || stripos($s, 'වයරින්') !== false ||
            stripos($s, 'කැඩී ඇත') !== false || stripos($s, 'අබලන්') !== false || stripos($s, 'ැබලන්') !== false) {
            
            if (stripos($s, 'Minor') !== false || stripos($s, 'සුළු') !== false) {
                return 'Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)';
            }
            return 'Needs Major Renovation (ප්‍රධාන අලුත්වැඩියා අවශ්‍යයි)';
        }

        // 4. Minor repairs
        if (stripos($s, 'Minor') !== false || stripos($s, 'සාමාන්‍ය') !== false || stripos($s, 'තීන්ත') !== false) {
            return 'Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)';
        }

        // 5. Good / Usable
        return 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)';
    }
}
