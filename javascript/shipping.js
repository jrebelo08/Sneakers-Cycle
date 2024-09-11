const countrySelect = document.getElementById('country');
const citySelect = document.getElementById('city');

countrySelect.addEventListener('change', () => {
    const country = countrySelect.value;
    citySelect.innerHTML = '';

    if (country === 'Portugal') {
        const options = ['Aveiro', 'Beja', 'Braga', 'Castelo Branco', 'Coimbra', 'Évora', 'Faro', 'Guarda', 'Leiria', 'Lisbon', 'Porto', 'Santarém', 'Setúbal', 'Viana do Castelo', 'Vila Real', 'Viseu'];
        options.forEach((option) => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.text = option;
            citySelect.appendChild(optionElement);
        });
    } else if (country === 'Spain') {
        const options = ['Madrid', 'Barcelona', 'Valencia'];
        options.forEach((option) => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.text = option;
            citySelect.appendChild(optionElement);
        });
    } else if (country === 'France') {
        const options = ['Paris', 'Lyon', 'Marseille'];
        options.forEach((option) => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.text = option;
            citySelect.appendChild(optionElement);
        });
    } else if (country === 'Germany') {
        const options = ['Berlin', 'Munich', 'Hamburg'];
        options.forEach((option) => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.text = option;
            citySelect.appendChild(optionElement);
        });
    } else if (country === 'Italy') {
        const options = ['Rome', 'Milan', 'Venice'];
        options.forEach((option) => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.text = option;
            citySelect.appendChild(optionElement);
        });
    } else if (country === 'United Kingdom') {
        const options = ['London', 'Manchester', 'Birmingham'];
        options.forEach((option) => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.text = option;
            citySelect.appendChild(optionElement);
        });
    }
});
