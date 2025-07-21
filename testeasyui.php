import { Component } from '@angular/core';
import { DataService } from './data.service';
 
@Component({
    selector: 'app-root',
    template: `
        <h2>Pagination</h2>
        <div style="margin-bottom:10px">
            <label [for]="c1">Pager on: </label>
            <eui-combobox #c1 style="width:120px"
                    [data]="pageOptions" 
                    [(ngModel)]="pagePosition" 
                    [editable]="false"
                    [panelStyle]="{height:'auto'}">
            </eui-combobox>
        </div>
        <eui-datagrid style="height:250px"
                [pagination]="true"
                [total]="total"
                [pageNumber]="pageNumber"
                [pageSize]="pageSize"
                [data]="data"
                [pagePosition]="pagePosition">
            <eui-grid-column field="inv" title="Inv No"></eui-grid-column>
            <eui-grid-column field="name" title="Name"></eui-grid-column>
            <eui-grid-column field="amount" title="Amount" align="right"></eui-grid-column>
            <eui-grid-column field="price" title="Price" align="right"></eui-grid-column>
            <eui-grid-column field="cost" title="Cost" align="right"></eui-grid-column>
            <eui-grid-column field="note" title="Note"></eui-grid-column>
        </eui-datagrid>
    `,
    providers: [DataService]
})
export class AppComponent {
    total: number = 0;
    pageNumber = 1;
    pageSize = 20;
    data = [];
    pagePosition: string = 'bottom';
    pageOptions = [{value:'bottom',text:'Bottom'},{value:'top',text:'Top'},{value:'both',text:'Both'}];
 
    constructor(public dataService: DataService){}
 
    ngAfterViewInit() {
        this.dataService.getData().subscribe((data) => {
            this.total = data.total;
            this.data = data.rows;
        });
    }
}