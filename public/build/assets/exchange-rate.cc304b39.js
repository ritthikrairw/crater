import{a as i,d as g}from"./vendor.25b592b0.js";import{h as c,u as v}from"./main.0118a8f7.js";const x=(u=!1)=>{const o=u?window.pinia.defineStore:g,{global:n}=window.i18n,s=v();return o({id:"exchange-rate",state:()=>({supportedCurrencies:[],drivers:[],activeUsedCurrencies:[],providers:[],currencies:null,currentExchangeRate:{id:null,driver:"",key:"",active:!0,currencies:[]},currencyConverter:{type:"",url:""},bulkCurrencies:[]}),getters:{isEdit:r=>!!r.currentExchangeRate.id},actions:{fetchProviders(r){return new Promise((a,t)=>{i.get("/api/v1/exchange-rate-providers",{params:r}).then(e=>{this.providers=e.data.data,a(e)}).catch(e=>{c(e),t(e)})})},fetchDefaultProviders(){return new Promise((r,a)=>{i.get("/api/v1/config?key=exchange_rate_drivers").then(t=>{this.drivers=t.data.exchange_rate_drivers,r(t)}).catch(t=>{c(t),a(t)})})},fetchProvider(r){return new Promise((a,t)=>{i.get(`/api/v1/exchange-rate-providers/${r}`).then(e=>{this.currentExchangeRate=e.data.data,this.currencyConverter=e.data.data.driver_config,a(e)}).catch(e=>{c(e),t(e)})})},addProvider(r){return new Promise((a,t)=>{i.post("/api/v1/exchange-rate-providers",r).then(e=>{s.showNotification({type:"success",message:n.t("settings.exchange_rate.created_message")}),a(e)}).catch(e=>{c(e),t(e)})})},updateProvider(r){return new Promise((a,t)=>{i.put(`/api/v1/exchange-rate-providers/${r.id}`,r).then(e=>{s.showNotification({type:"success",message:n.t("settings.exchange_rate.updated_message")}),a(e)}).catch(e=>{c(e),t(e)})})},deleteExchangeRate(r){return new Promise((a,t)=>{i.delete(`/api/v1/exchange-rate-providers/${r}`).then(e=>{let h=this.drivers.findIndex(d=>d.id===r);this.drivers.splice(h,1),e.data.success?s.showNotification({type:"success",message:n.t("settings.exchange_rate.deleted_message")}):s.showNotification({type:"error",message:n.t("settings.exchange_rate.error")}),a(e)}).catch(e=>{c(e),t(e)})})},fetchCurrencies(r){return new Promise((a,t)=>{i.get("/api/v1/supported-currencies",{params:r}).then(e=>{this.supportedCurrencies=e.data.supportedCurrencies,a(e)}).catch(e=>{c(e),t(e)})})},fetchActiveCurrency(r){return new Promise((a,t)=>{i.get("/api/v1/used-currencies",{params:r}).then(e=>{this.activeUsedCurrencies=e.data.activeUsedCurrencies,a(e)}).catch(e=>{c(e),t(e)})})},fetchBulkCurrencies(){return new Promise((r,a)=>{i.get("/api/v1/currencies/used").then(t=>{this.bulkCurrencies=t.data.currencies.map(e=>(e.exchange_rate=null,e)),r(t)}).catch(t=>{c(t),a(t)})})},updateBulkExchangeRate(r){return new Promise((a,t)=>{i.post("/api/v1/currencies/bulk-update-exchange-rate",r).then(e=>{a(e)}).catch(e=>{c(e),t(e)})})},getCurrentExchangeRate(r){return new Promise((a,t)=>{i.get(`/api/v1/currencies/${r}/exchange-rate`).then(e=>{a(e)}).catch(e=>{t(e)})})},getCurrencyConverterServers(){return new Promise((r,a)=>{i.get("/api/v1/config?key=currency_converter_servers").then(t=>{r(t)}).catch(t=>{c(t),a(t)})})},checkForActiveProvider(r){return new Promise((a,t)=>{i.get(`/api/v1/currencies/${r}/active-provider`).then(e=>{a(e)}).catch(e=>{t(e)})})}}})()};export{x as u};