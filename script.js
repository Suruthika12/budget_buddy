function formatDate(dateStr) {
  const [year, month, day] = dateStr.split("-");
  return `${day}-${month}-${year}`;
}

function generateSuggestions(data, income, expense) {
  const suggestions = [];

  if (income === 0) {
    suggestions.push("You haven't added any income. Add sources to see a realistic balance.");
  }

  if (expense > income) {
    suggestions.push("You're spending more than you earn. Consider reducing expenses.");
  }

  const categoryTotals = {};
  data.forEach(item => {
    if (item.type === "expense") {
      categoryTotals[item.category] = (categoryTotals[item.category] || 0) + parseFloat(item.amount);
    }
  });

  const sorted = Object.entries(categoryTotals).sort((a, b) => b[1] - a[1]);
  if (sorted.length > 0 && sorted[0][1] > 0.3 * expense) {
    suggestions.push(`High spending detected in "${sorted[0][0]}". Consider budgeting this category.`);
  }

  const ul = document.getElementById("suggestionsList");
  if (!ul) return;
  ul.innerHTML = "";

  if (suggestions.length === 0) {
    ul.innerHTML = "<li>You're doing great! Keep tracking your expenses. ✅</li>";
  } else {
    suggestions.forEach(tip => {
      const li = document.createElement("li");
      li.textContent = tip;
      ul.appendChild(li);
    });
  }
}

function renderExpenseChart(categoryMap) {
  const canvas = document.getElementById("expenseChart");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");
  const labels = Object.keys(categoryMap);
  const values = Object.values(categoryMap);

  if (chartInstance) chartInstance.destroy();

  chartInstance = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [{
        label: "Expenses by Category",
        data: values,
        backgroundColor: "#e74c3c",
        barPercentage: 0.3,
        categoryPercentage: 0.5
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function (context) {
              return `₹${context.raw}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Amount (₹)'
          }
        }
      }
    }
  });
}


function loadTransactions() {
  const type = document.getElementById("filterType")?.value || "";
  const category = document.getElementById("filterCategory")?.value || "";
  const startDate = document.getElementById("startDate")?.value || "";
  const endDate = document.getElementById("endDate")?.value || "";

  const formData = new FormData();
  formData.append("type", type);
  formData.append("category", category);
  formData.append("startDate", startDate);
  formData.append("endDate", endDate);

  fetch("fetch_expenses.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      const ul = document.getElementById("transactions");
      if (ul) ul.innerHTML = "";

      let totalIncome = 0;
      let totalExpense = 0;
      const categoryMap = {};

      data.forEach((item) => {
        const amount = parseFloat(item.amount);

        if (item.type === "income") totalIncome += amount;
        else {
          totalExpense += amount;
          categoryMap[item.category] = (categoryMap[item.category] || 0) + amount;
        }

        if (ul) {
          const li = document.createElement("li");
          li.classList.add(item.type);
          li.innerHTML = `
            <strong>${item.type.toUpperCase()}</strong> | ${item.category} | ₹${item.amount} <br>
            <em>${formatDate(item.date)}</em> - ${item.description || "No description"}
            <button class="delete-btn" data-id="${item.id}">Delete</button>
          `;
          ul.appendChild(li);
        }
      });

      // Update summary
      const incomeEl = document.getElementById("totalIncome");
      const expenseEl = document.getElementById("totalExpense");
      const balanceEl = document.getElementById("balance");

      if (incomeEl) incomeEl.textContent = totalIncome.toFixed(2);
      if (expenseEl) expenseEl.textContent = totalExpense.toFixed(2);
      if (balanceEl) balanceEl.textContent = (totalIncome - totalExpense).toFixed(2);

      // Add delete buttons
      if (ul) {
        document.querySelectorAll(".delete-btn").forEach((btn) => {
          btn.addEventListener("click", function () {
            const id = this.dataset.id;
            fetch("delete_expense.php", {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: `id=${id}`
            })
              .then(res => res.text())
              .then(msg => {
                alert(msg);
                loadTransactions();
              })
              .catch(err => console.error("Delete error:", err));
          });
        });
      }

      // AI Suggestions (Dashboard only)
      if (document.getElementById("suggestionsList")) {
        generateSuggestions(data, totalIncome, totalExpense);
      }
    })
    .catch((err) => console.error("Fetch error:", err));
}

document.addEventListener("DOMContentLoaded", function () {
  loadTransactions();

  const form = document.getElementById("transactionForm");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(form);

      fetch("add_expense.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.text())
        .then((data) => {
          alert(data);
          form.reset();
          loadTransactions();
        })
        .catch((err) => console.error("Add transaction error:", err));
    });
  }
});
