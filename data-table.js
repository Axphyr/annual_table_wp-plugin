function limitWords() {
    const input = document.getElementById("theme_recherche");
    const words = input.value.split(" ");
    if (words.length > 80) {
    input.value = words.slice(0, 80).join(" ");
    }
}

// Function to update the progress bar
function updateProgressBar() {
    const progressBar = document.getElementById('progress-bar');
    const percentElement = document.getElementById('pourcent');
    const annualDataTableSummary = document.querySelector('.annual_data_table_summary');
    try {
        const customTotalElements = annualDataTableSummary.querySelectorAll('button').length;
        const totalElements = parseInt(document.querySelector('.nb__buttons').textContent.trim());
        const dtGreenElements = document.querySelectorAll('.dt__green');
        const progress = (customTotalElements.length === 1) ? 0 : ((dtGreenElements.length) / (totalElements)) * 100;
        progressBar.style.width = progress + '%';
        percentElement.textContent = Math.floor(progress) + '%';
    } catch (e){}
}

// Initial update
updateProgressBar();

function getFillAnimationRule(counter) {
    const styleSheets = document.styleSheets;
    let fillAnimationRule = null;
    let count = 1;

    // Iterate through all style sheets
    for (let i = 0; i < styleSheets.length; i++) {
        const styleSheet = styleSheets[i];

        // Check if the style sheet is a CSSStyleSheet
        if (styleSheet instanceof CSSStyleSheet) {
            const cssRules = styleSheet.cssRules || styleSheet.rules;

            // Iterate through all CSS rules in the style sheet
            for (let j = 0; j < cssRules.length; j++) {
                const rule = cssRules[j];

                // Check if the rule is a CSSStyleRule and matches the desired selector
                if (rule instanceof CSSStyleRule && rule.selectorText === '.circle-wrap .circle .mask.full, .circle-wrap .circle .fill' || rule.selectorText === '.circle-wrap2 .circle .mask.full, .circle-wrap2 .circle .fill' || rule.selectorText === '.circle-wrap3 .circle .mask.full, .circle-wrap3 .circle .fill') {

                    // Check if the rule contains the fill animation property
                    if (rule.style.animationName === 'fill') {
                        if(count === counter){
                            fillAnimationRule = rule;
                            break;
                        } else {
                            count++;
                        }
                    }
                }
            }
        }
    }

    return fillAnimationRule;
}

try{
    const fillAnimationRule = getFillAnimationRule(1);
    const rotationValue = parseInt((document.querySelector(".inside-circle")).textContent.trim(), 10) / 100 * 180;
    if (fillAnimationRule) {
        fillAnimationRule.style.transform = 'rotate(' + rotationValue + 'deg)';
    }

    const fillAnimationRule2 = getFillAnimationRule(2);
    const rotationValue2 = parseInt((document.querySelector(".inside-circle2")).textContent.trim(), 10) / 100 * 180;
    if (fillAnimationRule2) {
        fillAnimationRule2.style.transform = 'rotate(' + rotationValue2 + 'deg)';
    }

    const fillAnimationRule3 = getFillAnimationRule(3);
    const rotationValue3 = parseInt((document.querySelector(".inside-circle3")).textContent.trim(), 10) / 100 * 180;
    if (fillAnimationRule3) {
        fillAnimationRule3.style.transform = 'rotate(' + rotationValue3 + 'deg)';
    }
} catch (e){}



function confirmDelete() {
    return window.confirm("Voulez-vous vraiment supprimer vos données?");
}

const currentPage = window.location.pathname;

if (currentPage.startsWith('/dt-')) {
    const form = document.querySelector('.data-table-form');

    if (form) {
        const labels = form.querySelectorAll('label');
        console.log(labels);
        labels.forEach(function (label) {
            const inputId = label.getAttribute('for');
            const input = form.querySelector('#' + inputId);

            if (input && input.required) {
                const asterisk = document.createElement('span');
                asterisk.textContent = '*';
                asterisk.style.color = 'red';
                asterisk.style.marginLeft = '5px';

                label.appendChild(asterisk);
            }
        });
    }
}

if (window.location.pathname === "/dt-formulaire-publications-2/") {
    const formSections = Array.from(document.querySelectorAll("#hceres__var__form .dt__year"));
    const momther = document.querySelector(".dt__wt__buttons");
    const showButton = document.getElementById("showBtn");
    const hideButton = document.getElementById("hideBtn");
    const dtYearElements = document.querySelectorAll('.dt__year');
    hideButton.disabled = true;
    hideButton.style.backgroundColor = "gray";

    formSections.slice(1).forEach(element => {
        element.parentNode.removeChild(element);
    });

    formSections.shift();
    formSections.reverse();

    showButton.addEventListener("click", showNextSection);
    hideButton.addEventListener("click", hideLastSection);
    showButton.addEventListener("click", buttonsColor);
    hideButton.addEventListener("click", buttonsColor);
    document.addEventListener("DOMContentLoaded", buttonsColor);

    for (let i = 1; i < dtYearElements.length; i++) {
        const firstInputElement = dtYearElements[i].querySelector('input');
        if (firstInputElement && firstInputElement.value.trim() !== '') {
            momther.appendChild(formSections[formSections.length - 1]);
            formSections.pop();
        }
    }

    function showNextSection() {
        if (formSections.length !== 0) {
            momther.appendChild(formSections[formSections.length - 1]);
            formSections.pop()
        }
    }
    console.log(formSections);
    function buttonsColor() {
        const sections = Array.from(document.querySelectorAll("#hceres__var__form .dt__year"));
        console.log(sections);
        if (formSections.length === 0) {
        showButton.disabled = true;
        showButton.style.backgroundColor = "gray";
        hideButton.disabled = false;
        hideButton.style.backgroundColor = "#2f5aae";
        }
        else if (sections.length <= 1) {
            showButton.disabled = false;
            showButton.style.backgroundColor = "#2f5aae";
            hideButton.disabled = true;
            hideButton.style.backgroundColor = "gray";
        }
        else {
            showButton.disabled = false;
            showButton.style.backgroundColor = "#2f5aae";
            hideButton.disabled = false;
            hideButton.style.backgroundColor = "#2f5aae";
        }
    }

    function hideLastSection() {
        const buttonsDiv = Array.from(document.querySelectorAll("#hceres__var__form .dt__year"));
        buttonsDiv.shift()
        const sections = Array.from(document.querySelectorAll("#hceres__var__form .dt__year"));
        if (sections.length > 1){
            formSections.push(buttonsDiv[buttonsDiv.length - 1]);
            buttonsDiv[buttonsDiv.length - 1].remove();
        }
    }
}

if (window.location.pathname === "/dt-formulaire-discipline/"){
    function disableOptions(select1, select2) {
        const selectedValue1 = select1.value;
        const selectedValue2 = select2.value;

        select1.querySelectorAll('option').forEach(function(option) {
            option.disabled = (option.value === selectedValue2 && selectedValue2 !== '');
        });

        select2.querySelectorAll('option').forEach(function(option) {
            option.disabled = (option.value === selectedValue1 && selectedValue1 !== '');
        });
    }

    const select1 = document.getElementById('discipline1');
    const select2 = document.getElementById('discipline2');

    // Call the disableOptions function on page load
    disableOptions(select1, select2);

    select1.addEventListener('change', function() {
        disableOptions(select1, select2);
    });

    select2.addEventListener('change', function() {
        disableOptions(select1, select2);
    });
}

if (!document.querySelector(".dt__green")) {
    const deleteElement = document.querySelector(".donnees__hceres__delete");
    if (deleteElement) {
        deleteElement.style.display = "none";
    }
}

// Check if a form with class "data-table-form" exists
const dataTableForm = document.querySelector('.data-table-form');
if (dataTableForm) {
    // Create the new element with the desired structure and styles
    const messageElement = document.createElement('p');
    messageElement.innerHTML = 'Les champs marqués par un<span style="color: red; margin-left: 5px;">*</span> sont obligatoires.';

    // Find the element with class "nv-page-title"
    const pageTitleElement = document.querySelector('.nv-page-title');

    // Insert the new element after the "nv-page-title" element
    pageTitleElement.insertAdjacentElement('afterend', messageElement);
}


