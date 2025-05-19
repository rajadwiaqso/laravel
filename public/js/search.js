


const searchButtons = document.getElementById('navButton');
const searchInputs = document.getElementById('navInput')

searchButtons.addEventListener('click', function() {
    const query = searchInputs.value;
    window.location.href = `/products/search?query=${query}`;
    
});