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
    const customTotalElements = annualDataTableSummary.querySelectorAll('button').length;
    const totalElements = parseInt(document.querySelector('.nb__buttons').textContent.trim());
    const dtGreenElements = document.querySelectorAll('.dt__green');
    const progress = (customTotalElements.length === 1) ? 0 : ((dtGreenElements.length) / (totalElements)) * 100;
    progressBar.style.width = progress + '%';
    percentElement.textContent = Math.floor(progress) + '%';
}

// Initial update
updateProgressBar();

function getFillAnimationRule() {
    const styleSheets = document.styleSheets;
    let fillAnimationRule = null;

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
                if (rule instanceof CSSStyleRule && rule.selectorText === '.circle-wrap .circle .mask.full, .circle-wrap .circle .fill') {

                    // Check if the rule contains the fill animation property
                    if (rule.style.animationName === 'fill') {
                        fillAnimationRule = rule;
                        break;
                    }
                }
            }
        }
    }

    return fillAnimationRule;
}

const fillAnimationRule = getFillAnimationRule();
const rotationValue = parseInt((document.querySelector(".inside-circle")).textContent.trim(), 10) / 100 * 180;
if (fillAnimationRule) {
    fillAnimationRule.style.transform = 'rotate(' + rotationValue + 'deg)';
}
