import * as pdfMake from 'pdfmake/build/pdfmake';
import * as pdfFonts from 'pdfmake/build/vfs_fonts';
import { __DataTableColumnType } from './dataTable.types';

const temp: any = pdfMake;
temp.vfs = pdfFonts.pdfMake.vfs;

// no comment for this algorithm, same as previous version(copy)
export function __tablePDFGenerator<T>(data: T[], columns: __DataTableColumnType[]) {
    const content: string[][] = [];
    const header: string[] = [];

    for (const col of columns) {
        header.push(col.title);
    }
    content.push(header);
    for (const row of data) {
        const arr = [];
        for (const col of columns) {
            const value = col.stringContent(row);
            arr.push(value ? value : '');
        }
        content.push(arr);
    }

    const docDefinition: any = {
        content: [
            { text: 'Tables', style: 'header' },
            'Official documentation is in progress, this document is just a glimpse of what is possible with pdfmake and its layout engine.',
            {
                style: 'tableExample',
                table: {
                    body: content,
                },
            },
        ],
        styles: {
            header: {
                fontSize: 18,
                bold: true,
                margin: [0, 0, 0, 10],
            },
            subheader: {
                fontSize: 16,
                bold: true,
                margin: [0, 10, 0, 5],
            },
            tableExample: {
                margin: [0, 5, 0, 15],
            },
            tableOpacityExample: {
                margin: [0, 5, 0, 15],
                fillColor: 'blue',
                fillOpacity: 0.3,
            },
            tableHeader: {
                bold: true,
                fontSize: 13,
                color: 'black',
            },
        },
        defaultStyle: {
            // alignment: 'justify'
        },
    };

    pdfMake.createPdf(docDefinition).open();
}
