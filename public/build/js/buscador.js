function iniciarApp(){filtrarPorFecha()}function filtrarPorFecha(){document.querySelector("#fecha").addEventListener("input",(function(n){const t=n.target.value;window.location="?fecha="+t}))}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));