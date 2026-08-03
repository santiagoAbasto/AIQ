const sidebarToggle = document.querySelector('#sidebarToggle'); 
const sidebar = document.getElementById('sidebar');
sidebarToggle.addEventListener('click', ()=>{
  sidebar.classList.toggle('hide'); 
})