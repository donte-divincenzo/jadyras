document.addEventListener("DOMContentLoaded", function() {
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const menuContainer = document.querySelector('.menu-container');
    // const hamburgerClose = document.querySelector('.hamburger-close');
    const links = document.querySelectorAll(".menu-item")

    const toggleMenu = () => {
        menuContainer.classList.toggle('active');
        hamburgerMenu.classList.toggle('active');
    };

    hamburgerMenu.addEventListener('click', toggleMenu);
    // hamburgerClose.addEventListener('click', toggleMenu);
    // links.addEventListener('click', toggleMenu)
});

async function fetchData() {
    try {
        const response = await fetch('server.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ request: 'getData' })
        });

        const data = await response.json();
        processServerData(data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function processServerData(data) {
    if (data.success) {
        updateDOMElements(data.payload);
    } else {
        console.warn('Data processing error:', data.message);
    }
}

function updateDOMElements(payload) {
    const container = document.getElementById('data-container');
    container.innerHTML = '';

    payload.forEach(item => {
        const element = document.createElement('div');
        element.className = 'data-item';
        element.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.description}</p>
        `;
        container.appendChild(element);
    });

    setTimeout(() => {
        console.log('Additional asynchronous task completed.');
    }, 1000);
}

document.addEventListener('DOMContentLoaded', () => {
    fetchData();

    document.querySelectorAll('.data-item').forEach(item => {
        item.addEventListener('click', () => {
            console.log('Data item clicked:', item);
        });
    });
});

async function sendData() {
    try {
        const response = await fetch('server.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ request: 'sendData', data: { key: 'value' } })
        });

        const result = await response.json();
        if (result.success) {
            console.log('Data sent successfully.');
        } else {
            console.error('Error sending data:', result.message);
        }
    } catch (error) {
        console.error('Error sending data:', error);
    }
}

const emailbtn = document.getElementById('send-btn')
emailbtn.addEventListener("click", () => {
    const emailspan = document.getElementById('span')
    setTimeout(() => {
        emailspan.textContent = 'Отправлено!'
    }, 4000);
})
fetchData();
sendData();

