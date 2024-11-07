function() {
    let table = document.getElementById('data-table');
        let cloneTable = table.cloneNode(true);
        table.style.display = 'none';

        let content = cloneTable.getElementById('content');
        let action = content.getElementById('actions');
        let action-btn = content.getElementById('action-btn')
        content.removeChild(action);
        content.removeChild(action-btn);

        pdf-btn = document.getElementById('pdf-btn');
        pdf-btn.addEventListener("click",function() {
            html2pdf(content);
        });
}

