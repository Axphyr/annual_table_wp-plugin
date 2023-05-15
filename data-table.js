
function limitWords() {
    const input = document.getElementById("theme_recherche");
    const words = input.value.split(" ");
    if (words.length > 80) {
    input.value = words.slice(0, 80).join(" ");
    }
}

// Function to update the progress bar
function updateProgressBar() {
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const pourcentElement = document.getElementById('pourcent');
    const annualDataTableSummary = document.querySelector('.annual_data_table_summary');
    const customTotalElements = annualDataTableSummary.querySelectorAll('button').length;
    const totalElements = parseInt(document.querySelector('.nb__buttons').textContent.trim());
    const dtGreenElements = document.querySelectorAll('.dt__green');
    const progress = (customTotalElements.length === 1) ? 0 : ((dtGreenElements.length) / (totalElements)) * 100;
    progressBar.style.width = progress + '%';
    pourcentElement.textContent = Math.floor(progress) + '%';
}

// Initial update
updateProgressBar();