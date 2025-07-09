document.addEventListener('DOMContentLoaded', () => {
    const botaoPDF = document.getElementById('gerar-pdf');

    if (!botaoPDF) return;

    botaoPDF.addEventListener('click', async () => {
        const { jsPDF } = window.jspdf;

        const noticia = document.querySelector('.main-content');

        // Clona o conteúdo para não afetar a página visível
        const clone = noticia.cloneNode(true);

        // Remove o botão do clone
        const botaoClone = clone.querySelector('#gerar-pdf');
        if (botaoClone) botaoClone.remove();

        // Cria um contêiner invisível
        const container = document.createElement('div');
        container.style.top = '-8000px';
        container.style.left = '-8000px';
        container.style.width = '1000px';
        container.appendChild(clone);
        document.body.appendChild(container);

        try {
            const canvas = await html2canvas(clone, {
                scale: 2,
                useCORS: true
            });

            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const margemX = 15;
            const margemY = 20;

            const areaLargura = pageWidth - 2 * margemX;
            const areaAltura = pageHeight - 2 * margemY;

            const imgWidth = canvas.width;
            const imgHeight = canvas.height;
            const escala = areaLargura / imgWidth;
            const alturaRedimensionada = imgHeight * escala;

            let heightLeft = alturaRedimensionada;
            let position = margemY;
            let pageNum = 0;

            while (heightLeft > 0) {
                if (pageNum > 0) pdf.addPage();

                pdf.addImage(
                    imgData,
                    'PNG',
                    margemX,
                    position,
                    areaLargura,
                    alturaRedimensionada
                );

                heightLeft -= areaAltura;
                pageNum++;
                position = margemY - (alturaRedimensionada - heightLeft);
            }

            pdf.save("noticia.pdf");
        } catch (error) {
            console.error("Erro ao gerar PDF:", error);
            alert("Erro ao gerar PDF.");
        } finally {
            document.body.removeChild(container);
        }
    });
});
