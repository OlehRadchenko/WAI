declare const $: any;

interface User {
    nick: string;
    avatar: string;
}

interface Polow {
    title: string;
    description: string;
    wagaRyby: string;
    photoData: string | null;
    date: string;
    dataPolowu: string | null;
    rating?: number;
}

document.querySelectorAll(".js-only").forEach(element => {
    (element as HTMLElement).style.display = "flex";
});

($("#dataPolowu") as any).datepicker({ dateFormat: "dd-mm-yy" });
($("#wagaRyby") as any).slider({
    range: "min",
    value: 1,
    min: 0.5,
    max: 50,
    step:0.1,
    slide: function(event: any, ui: any) {
        $("#wagaRybyValue").text(ui.value + " kg");
    }
});
($("#emojiRating") as any).emoji({
    width: '32px',
    value: 0,
    emojis: ['&#x1F620;','&#x1F61E;','&#x1F610;','&#x1F60A;','&#x1F603;'],
    callback: function(event: any, value: any) {
        $("#rating").val(value);
    }
});

if (!localStorage.getItem("user")) {
    const exampleUser: User = {
        nick: "RyboŁapek99",
        avatar: "https://cdn-icons-png.flaticon.com/512/616/616408.png"
    };
    localStorage.setItem("user", JSON.stringify(exampleUser));
}

const form = document.getElementById('catchForm') as HTMLFormElement;
const polowyDiv = document.getElementById('polowy') as HTMLDivElement;

form.addEventListener('submit', function(event) {
    event.preventDefault();

    const title = (document.getElementById('title') as HTMLInputElement).value.trim();
    const description = (document.getElementById('description') as HTMLInputElement).value.trim();
    const dataPolowu = (document.getElementById('dataPolowu') as HTMLInputElement).value.trim();
    const rating = Number((document.getElementById('rating') as HTMLInputElement).value);
    const wagaRyby = (document.getElementById("wagaRybyValue") as HTMLElement).textContent;
    const photoInput = document.getElementById('photo') as HTMLInputElement;

    const reader = new FileReader();

    const savePolow = (photoData: string | null) => {
        const newPolow: Polow = { title, description, wagaRyby, photoData, date: new Date().toLocaleString(), dataPolowu: dataPolowu || null, rating };
        const polowy: Polow[] = JSON.parse(localStorage.getItem('polowy') || '[]');
        polowy.push(newPolow);
        localStorage.setItem('polowy', JSON.stringify(polowy));
        form.reset();
        displayEntries();
    };

    reader.onload = () => savePolow(reader.result as string);
    reader.onerror = () => { alert("Nie udało się wczytać pliku!"); };

    if (photoInput.files && photoInput.files[0]) {
        reader.readAsDataURL(photoInput.files[0]);
    } else {
        savePolow(null);
    }
});

function createPolowElement(polow: Polow, user: User): HTMLElement {
    const div = document.createElement('div');
    div.classList.add('polow');

    const img = document.createElement('img');
    img.src = user.avatar;
    img.alt = "avatar";
    img.classList.add("profile-pic");

    const content = document.createElement('div');
    content.classList.add("polow-content");

    const nickname = document.createElement('div');
    nickname.classList.add("nickname");
    nickname.textContent = `${user.nick} złowił rybę o wadze ${polow.wagaRyby}${polow.rating ? ` - Ocena połowu: ${polow.rating}` : ""}`;

    const date = document.createElement('div');
    date.classList.add("date");
    date.textContent = `data opisu połowu: ${polow.date}${polow.dataPolowu ? `, data połowu: ${polow.dataPolowu}` : ""}`;

    const title = document.createElement('h3');
    title.textContent = polow.title;

    const desc = document.createElement('p');
    desc.textContent = polow.description;

    content.append(nickname, date, title, desc);

    if (polow.photoData) {
        const photo = document.createElement('img');
        photo.src = polow.photoData;
        photo.classList.add("catch-img");
        content.appendChild(photo);
    }

    div.append(img, content);
    return div;
}

function displayEntries() {
    const user: User | null = JSON.parse(localStorage.getItem('user') || 'null');
    if (!user) return;

    const polowy: Polow[] = JSON.parse(localStorage.getItem('polowy') || '[]');
    polowyDiv.innerHTML = "";

    polowy.slice().reverse().forEach(polow => {
        polowyDiv.appendChild(createPolowElement(polow, user));
    });
}

displayEntries();