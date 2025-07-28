const brandFilter = document.getElementById("brandFilter");
const categoryFilter = document.getElementById("categoryFilter");
const priceFilter = document.getElementById("priceFilter");
const productCards = document.querySelectorAll(".product-card");

function filterProducts() {
  const brand = brandFilter.value.toLowerCase();
  const category = categoryFilter.value.toLowerCase();
  const price = priceFilter.value;

  productCards.forEach(card => {
    const cardBrand = (card.dataset.brand || "").toLowerCase();
    const cardCategory = (card.dataset.category || "").toLowerCase();
    const cardPrice = card.dataset.price;

    const matchesBrand = brand === "all" || cardBrand === brand;
    const matchesCategory = category === "all" || cardCategory === category;
    const matchesPrice = price === "all" || cardPrice === price;

    if (matchesBrand && matchesCategory && matchesPrice) {
      card.style.display = "flex"; // Adjust to match your layout
    } else {
      card.style.display = "none";
    }
  });
}

brandFilter.addEventListener("change", filterProducts);
categoryFilter.addEventListener("change", filterProducts);
priceFilter.addEventListener("change", filterProducts);
