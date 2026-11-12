import naja from "naja";
import {NajaAjax} from "../../assets/ajax/naja";
import {AutosubmitPlugin} from "../../assets/plugins/features/autosubmit";

const ajax = new NajaAjax(naja);
ajax.onInit();
naja.initialize();
const plugin = new AutosubmitPlugin(300);
// The same object is passed on a reinitialization, as the real Datagrid passes itself.
const datagrid = {el: document.querySelector("#grid")!, ajax} as any;
plugin.onDatagridInit(datagrid);
Object.assign(window, {naja, ajax, plugin, datagrid});
