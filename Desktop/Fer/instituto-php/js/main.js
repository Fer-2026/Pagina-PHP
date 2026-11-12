
const filtro=document.getElementById("filtro");
if(filtro){filtro.addEventListener("change",()=>{const v=filtro.value;let n=0;
document.querySelectorAll(".evlist li").forEach(li=>{const ok=v==="all"||li.dataset.type===v;li.hidden=!ok;if(ok)n++;});
document.getElementById("estado").textContent=v==="all"?"":`Mostrando ${n} eventos de tipo: ${filtro.options[filtro.selectedIndex].text}.`;});}
// Envío simulado solo para los formularios estáticos de la maqueta:
// usan action="#" o no tienen atributo action. Los formularios reales
// (registro, login y CRUD) apuntan a una URL del servidor.
const f=document.querySelector('form[action="#"], form:not([action])');
if(f){f.addEventListener("submit",e=>{e.preventDefault();alert("Maqueta: formulario sin backend.");});}
