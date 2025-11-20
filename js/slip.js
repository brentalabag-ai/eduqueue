// Show/hide other purpose field
document.getElementById('others-checkbox').addEventListener('change', function() {
     document.getElementById('other-purpose').style.display = this.checked ? 'block' : 'none';
});

// Initialize other purpose field visibility
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('other-purpose').style.display = 
        document.getElementById('others-checkbox').checked ? 'block' : 'none';
});