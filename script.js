function loadBoxes() {
    fetch("ads_api.php")
        .then(response => response.json())
        .then(boxes => {
            const boxContainer = document.getElementById("boxes");
            boxContainer.innerHTML = "";
            boxes.forEach(box => {
                const boxCard = document.createElement("div");
                boxCard.className = "box-card";
                boxCard.innerHTML = `
                <div class="close-btn" onclick="deleteBox(${box.id})">X</div>
                <h3>${box.title}</h3>
                <p>${box.description}</p>
                <small>Kategoria: ${box.category}</small>
                `;
                boxContainer.appendChild(boxCard);
            });
        });
}

function addBox() {
    const title = document.getElementById("title").value;
    const description = document.getElementById("description").value;
    const category = document.getElementById("category").value;

    fetch("ads_api.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({title, description, category})
    })
    .then(() => {
        document.getElementById("title").value = "";
        document.getElementById("description").value = "";
        document.getElementById("category").value = "kupno";
        loadBoxes();
    });
}

function deleteBox(id) {
    fetch(`ads_api.php?id=${id}`, {method: "DELETE"})
        .then(() => loadBoxes());
}

loadBoxes();
