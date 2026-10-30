<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('buildings')) {
            // Ensure status column allows up to 191 chars for bilingual strings
            Schema::table('buildings', function (Blueprint $table) {
                $table->string('status', 191)->default('Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)')->change();
            });

            $buildings = DB::table('buildings')->get();
            foreach ($buildings as $b) {
                $rawStatus = trim($b->status ?? '');
                $currentNotes = trim($b->notes ?? '');

                $classified = $this->classifyStatus($rawStatus);
                $canonicalStatus = $classified['status'];
                $remark = $classified['remark'];

                $updates = ['status' => $canonicalStatus];

                if (!empty($remark)) {
                    if (empty($currentNotes)) {
                        $updates['notes'] = $remark;
                    } elseif (stripos($currentNotes, $remark) === false) {
                        $updates['notes'] = $remark . ' | ' . $currentNotes;
                    }
                }

                DB::table('buildings')->where('id', $b->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    private function classifyStatus(?string $status): array
    {
        $s = trim($status ?? '');
        if (!$s) {
            return ['status' => 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)', 'remark' => null];
        }

        // 1. Condemned / Unusable
        if (stripos($s, 'Condemn') !== false || stripos($s, 'පරිහරණයට නොගනී') !== false || stripos($s, 'ඉවත් කළ යුතු') !== false) {
            $remark = (!in_array($s, ['Condemned / Unusable (භාවිතයෙන් ඉවත් කළ යුතු)'])) ? $s : null;
            return ['status' => 'Condemned / Unusable (භාවිතයෙන් ඉවත් කළ යුතු)', 'remark' => $remark];
        }

        // 2. Dilapidated / Dangerous
        if (stripos($s, 'Dangerous') !== false || stripos($s, 'Dilapidated') !== false ||
            stripos($s, 'ඉතා අබලන්') !== false || stripos($s, 'අයහපත්') !== false || 
            stripos($s, 'අබලන් තත්ත්වයේ') !== false) {
            $remark = (!in_array($s, ['Dangerous / Condemned', 'අබලන් තත්ත්වයේ පවතී.', 'අබලන් තත්ත්වයේ පවතී', 'Dilapidated / Dangerous (ඉතා අබලන් / අවදානම්)'])) ? $s : null;
            return ['status' => 'Dilapidated / Dangerous (ඉතා අබලන් / අවදානම්)', 'remark' => $remark];
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
            
            // If it's just minor repair
            if (stripos($s, 'Minor') !== false || stripos($s, 'සුළු') !== false) {
                $remark = (!in_array($s, ['Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)'])) ? $s : null;
                return ['status' => 'Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)', 'remark' => $remark];
            }
            $remark = (!in_array($s, ['Needs Major Renovation', 'අලුත්වැඩියා කළ යුතුයි', 'අලුත්වැඩියා කල යුතුව ඇත.', 'Needs Major Renovation (ප්‍රධාන අලුත්වැඩියා අවශ්‍යයි)'])) ? $s : null;
            return ['status' => 'Needs Major Renovation (ප්‍රධාන අලුත්වැඩියා අවශ්‍යයි)', 'remark' => $remark];
        }

        // 4. Minor repairs
        if (stripos($s, 'Minor') !== false || stripos($s, 'සාමාන්‍ය') !== false || stripos($s, 'තීන්ත') !== false) {
            $remark = (!in_array($s, ['Minor Repairs Needed', 'සාමාන්‍ය මට්ටමක ඇත.', 'සාමාන්‍යයයි', 'Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)'])) ? $s : null;
            return ['status' => 'Minor Repairs Needed (සුළු අලුත්වැඩියා අවශ්‍යයි)', 'remark' => $remark];
        }

        // 5. Good / Usable
        $remark = (!in_array($s, ['Good / Usable', 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)', 'හොදයි', 'හොද තත්ත්වයක පවතී.', 'භාවිතා කරයි.', 'භාවිතයට සුදුසු තත්ත්වයේ පවතී.', 'භාවිතා කල හැක', 'භාවිතයට ගනී', 'භාවිතා කරනු ලැබේ.', 'හොද මට්ටමේ ඇත.', 'හොද තත්ත්වයේ පවතී.', 'හොද තත්ත්වයේ පවතී', 'යහපත්', 'භාවිතයේ පවතී.', 'භාවිතයට ගත හැකි.', 'භාවිතා කරයි.හොද තත්ත්වයේ ඇත.', 'භාවිතා කරයි.හොද තත්ත්වයේ පවතී.'])) ? $s : null;
        return ['status' => 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)', 'remark' => $remark];
    }
};
