/**
 * Teacher Census PDF Generator for Wilgamuwa Education Zone
 * Builds official printable PDF copy in the browser using jsPDF & autoTable
 */

function subjFormat(v, p) {
    const parts = [
        v[p + 'section'],
        v[p + 'category'],
        v[p + 'code'],
        v[p + 'medium'],
        v[p + 'periods'] ? (v[p + 'periods'] + ' periods') : null
    ].filter(Boolean);
    return parts.join(' | ');
}

function getTeacherPdfRows(v, schoolName, otherSchoolName) {
    const r = [
        ["01. School Census No", v["census_no"]],
        ["02. Name of the School", schoolName || v["school_name"]],
        ["03. National Identity Card No", (v["nic"] || "").toUpperCase()],
        ["04. Pay Sheet / Emp No", v["paysheet"]],
        ["Personal file number", v["personal_file_no"]],
        ["05. Title", v["title"]],
        ["06. Name as the Paysheet", v["full_name"]],
        ["Date of birth", v["date_of_birth"]],
        ["WhatsApp number", v["whatsapp"]],
        ["Email address", v["email"]],
        ["Address", v["address"]],
        ["07. Position held in this school", v["position"]],
        ["08. Salary paid by", v["salaryby"]],
        ["09. Service base of this school", v["servicebased"]],
        ["10. Source of salary paid", v["salarysource"]],
        ["11. Gender", v["gender"]],
        ["12. Ethnicity", [v["ethnicity"], v["ethnicity_other"]].filter(Boolean).join(" - ")],
        ["13. Religion", [v["religion"], v["religion_other"]].filter(Boolean).join(" - ")],
        ["14. Current marital status", [v["marital"], v["marital_other"]].filter(Boolean).join(" - ")],
        ["15. Highest education qualification", v["eduqua"]],
        ["16. Subject stream in G.C.E. (A/L)", v["subject"]],
        ["17. Field of study in basic degree", [v["field"], v["field_other"]].filter(Boolean).join(" - ")],
        ["18. Highest professional qualification", [v["profqua"], v["profqua_other"]].filter(Boolean).join(" - ")],
        ["19. Current service", v["service"]],
        ["20. First appointment date", v["firstappoint"]],
        ["21. Current class / grade", v["class"]],
        ["22. Appointment / promotion date to class", v["clsappoint"]],
        ["23. Appointment subject", subjFormat(v, "appt_")],
        ["24. First most work involved", [v["mainduty"], v["mainduty_other"]].filter(Boolean).join(" - ")],
        ["25. First most teaching subject", subjFormat(v, "t1_")],
        ["26. Second most work involved", [v["secondduty"], v["secondduty_other"]].filter(Boolean).join(" - ")],
        ["27. Second most teaching subject", subjFormat(v, "t2_")],
        ["28. Capable subject 1", subjFormat(v, "able1_")],
        ["29. Capable subject 2", subjFormat(v, "able2_")],
        ["30. Professional qualifications possessed", (v["profall"] || "").split("|").filter(Boolean).join("; ")],
        ["31. Appointed / assigned date to this school", v["schappoint"]],
        ["32. Mobile phone no", v["mobile"] || v["phone"]],
        ["Attached to another school", v["otherschool"]],
        ["Attached school", otherSchoolName || v["otherschool_name"]],
        ["Attached days", (v["otherschool_days"] || "").split("|").filter(Boolean).join(", ")],
    ];

    return r.map(([q, a]) => [q, a ? String(a) : "-"]);
}

function buildTeacherPdf(v, schoolName, otherSchoolName) {
    if (!window.jspdf || !window.jspdf.jsPDF) {
        alert("PDF generator is loading. Please try again in a few seconds.");
        return null;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit: "mm", format: "a4" });

    const currentYear = new Date().getFullYear();
    const title = `Teacher / Principal Data Update - ${currentYear}/${currentYear + 1}`;

    doc.setProperties({ title: title, author: "Zonal Education Office - Wilgamuwa" });

    // Header
    doc.setFont("helvetica", "bold");
    doc.setFontSize(14);
    doc.text("Zonal Education Office - Wilgamuwa", 105, 18, { align: "center" });
    doc.setFontSize(11);
    doc.text(title, 105, 25, { align: "center" });

    doc.setFont("helvetica", "normal");
    doc.setFontSize(8);
    const dateStr = new Date().toLocaleString("en-GB", { timeZone: "Asia/Colombo" });
    doc.text(`Zone: 52204 | Generated: ${dateStr}`, 105, 31, { align: "center" });
    doc.line(14, 35, 196, 35);

    // Table
    const tableData = getTeacherPdfRows(v, schoolName, otherSchoolName);
    doc.autoTable({
        startY: 39,
        head: [["Question", "Answer"]],
        body: tableData,
        styles: { fontSize: 8.5, cellPadding: 1.8, overflow: "linebreak" },
        headStyles: { fillColor: [40, 60, 90] },
        columnStyles: { 0: { cellWidth: 68, fontStyle: "bold" } },
    });

    let y = doc.lastAutoTable.finalY + 10;
    if (y + 20 > 283) {
        doc.addPage();
        y = 20;
    }

    doc.setFontSize(9);
    doc.text("I certify that the above given information is true and accurate.", 14, y);
    doc.text("Date: ..............................        Signature: ..............................", 14, y + 12);

    return doc;
}

function downloadTeacherPdf(values, schoolName, otherSchoolName) {
    const doc = buildTeacherPdf(values, schoolName, otherSchoolName);
    if (!doc) return;
    const nic = values.nic || "form";
    doc.save(`teacher-${nic.toUpperCase()}.pdf`);
}

function previewTeacherPdf(values, schoolName, otherSchoolName, modalId = 'pdf-preview-modal') {
    const doc = buildTeacherPdf(values, schoolName, otherSchoolName);
    if (!doc) return;
    const blob = doc.output('blob');
    const blobUrl = URL.createObjectURL(blob);

    let modal = document.getElementById(modalId);
    if (!modal) {
        modal = document.createElement('div');
        modal.id = modalId;
        modal.className = "fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4";
        modal.innerHTML = `
            <div class="relative w-full max-w-5xl h-[90vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-base font-bold text-slate-800">Teacher Census PDF Preview</h3>
                    <div class="flex items-center gap-3">
                        <button id="modal-download-btn" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition">Download PDF</button>
                        <button onclick="document.getElementById('${modalId}').remove()" class="text-slate-400 hover:text-slate-600 p-1">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <iframe id="pdf-preview-frame" class="w-full flex-1 border-none" src="${blobUrl}"></iframe>
            </div>
        `;
        document.body.appendChild(modal);
        document.getElementById('modal-download-btn').onclick = function() {
            downloadTeacherPdf(values, schoolName, otherSchoolName);
        };
    } else {
        document.getElementById('pdf-preview-frame').src = blobUrl;
        modal.classList.remove('hidden');
    }
}
