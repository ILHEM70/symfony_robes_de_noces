function addToCart(id) {
  let button = document.querySelector("#bouton_panier");
  let url = button.getAttribute("data-url");

  fetch(url, {
    method: "post",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id }),
  })
    .then((result) => {
      return result.json();
    })
    .then((result) => {
      let paragraphe = document.querySelector("#success_add");
      paragraphe.style.display = "block";
      paragraphe.textContent = result.message;
    })
    .catch((error) => console.log(error));
}

function removeFromCart(event, id) {
  event.preventDefault();
  let url = document.querySelector("data");

  fetch(url, {
    method: "post",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id }),
  })
    .then((result) => {
      return result.json();
    })
    .then((result) => {
      
      console.log("ok");
    })
    .catch((error) => {
      console.log(error);
    });
}
