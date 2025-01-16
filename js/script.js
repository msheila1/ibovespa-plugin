document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("ibovespaChart").getContext("2d");
    const apiUrl = `${ibovespaAjax.ajax_url}?action=ibovespa_data`;

    fetch(apiUrl)
        .then((response) => response.json())
        .then((data) => {
            console.log("Resposta da API:", data); // Log to check all returned data

            if (data.success && data.data.results.stocks.IBOVESPA) {
                const ibovespa = data.data.results.stocks.IBOVESPA;

                // Checking the data structure
                console.log("Dados do Ibovespa:", ibovespa); // Log to see how data is structured

                // Updating informations
                document.getElementById("ibovespaPoints").textContent = ibovespa.points || "N/A";
                document.getElementById("ibovespaVariation").textContent = `${ibovespa.variation || "N/A"}%`;
                document.getElementById("ibovespaUpdated").textContent = ibovespa.updated || "N/A"; // Updating

                // Chart setup
                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: ["Agora"],
                        datasets: [
                            {
                                label: `Ibovespa (${ibovespa.location})`,
                                data: [ibovespa.points],
                                borderColor: "rgba(75, 192, 192, 1)",
                                backgroundColor: "rgba(75, 192, 192, 0.2)",
                                borderWidth: 3,
                                tension: 0.4,
                                pointBackgroundColor: "rgba(255, 99, 132, 1)",
                                pointRadius: 5,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: "top",
                                labels: {
                                    color: "#333",
                                    font: {
                                        size: 14,
                                    },
                                },
                            },
                            tooltip: {
                                enabled: true,
                                backgroundColor: "rgba(0,0,0,0.7)",
                                titleColor: "#fff",
                                bodyColor: "#fff",
                            },
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: "rgba(200, 200, 200, 0.2)",
                                },
                                ticks: {
                                    color: "#555",
                                },
                            },
                            y: {
                                beginAtZero: false,
                                grid: {
                                    color: "rgba(200, 200, 200, 0.2)",
                                },
                                ticks: {
                                    color: "#555",
                                },
                            },
                        },
                    },
                });
            } else {
                console.error("Dados do Ibovespa não encontrados ou estrutura incorreta.");
            }
        })
        .catch((error) => {
            console.error("Erro ao buscar os dados:", error);
        });
});
