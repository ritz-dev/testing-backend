function generatePdf(id, list) {
    let barberTbl = document.getElementById(id);
    let cloneTbl = barberTbl.cloneNode(true);
    var wrapper = document.createElement('div');
    var title = document.createElement('h2');
    title.append(list)
    wrapper.appendChild(title);
    wrapper.appendChild(cloneTbl);
    rows = cloneTbl.rows;
    for(var i = 0; i<rows.length;i++) {
        j = rows[i].cells.length-1;
        rows[i].deleteCell(j);
    }

    html2pdf(wrapper);
}

