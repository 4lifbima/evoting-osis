<!-- Bottom Safe Area -->
<div class="flex justify-center py-3">
<div class="w-24 h-1 bg-gray-200 dark:bg-slate-600 rounded-full"></div>
</div>
</div>
<script>
function toggleTheme(){
    const h=document.documentElement;
    if(h.classList.contains('dark')){h.classList.remove('dark');localStorage.setItem('theme','light')}
    else{h.classList.add('dark');localStorage.setItem('theme','dark')}
}
(function(){if(localStorage.getItem('theme')==='dark')document.documentElement.classList.add('dark')})();
</script>
</body>
</html>
