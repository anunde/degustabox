"use strict"

document.addEventListener("DOMContentLoaded", () => {
    const startButton = document.getElementById("create-timer");
    const timerDisplay = document.getElementById("timer-display");
    const input = document.getElementById("task-name-input");
    const totalDisplay = document.getElementById("total");
    const taskContainer = document.getElementById("task-list-container");
    let timerInterval;

    fetchTasks();

    async function fetchTasks() {
        try {
            const response = await fetch("/tasks", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            });
    
            const result = await response.json();
    
            if (!result.status) {
                throw new Error(result.message);
            }
    
            renderTasks(result.data);
        } catch (error) {
            console.error("Error fetching tasks:", error);
            alert("Failed to fetch tasks. Please try again.");
        }
    }  

    startButton.addEventListener("click", async (event) => {
        event.preventDefault();

        startButton.disabled = true;
        const taskName = input.value.trim();

        if (!taskName) {
            alert("Please enter a task name before starting the timer.");
            startButton.disabled = false;
            return;
        }

        try {
            const response = await fetch("/timer/start", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ name: taskName }),
            });

            const result = await response.json();

            if (!result.status) {
                throw new Error(result.message);
            }

            startTimerDisplay(timerDisplay);
            createStopButton(startButton, timerInterval);

        } catch (error) {
            console.error("Error starting timer:", error);
            alert("Failed to start timer. Please try again.");
            startButton.disabled = false;
        }
    });

    function startTimerDisplay(timerDisplay) {
        let seconds = 0;

        clearInterval(timerInterval);

        timerInterval = setInterval(() => {
            seconds++;

            const hours = String(Math.floor(seconds / 3600)).padStart(2, "0");
            const minutes = String(Math.floor((seconds % 3600) / 60)).padStart(2, "0");
            const secs = String(seconds % 60).padStart(2, "0");

            timerDisplay.textContent = `${hours}:${minutes}:${secs}`;
        }, 1000);
    }

    function createStopButton(startButton, timerInterval) {
        const stopButton = document.createElement("button");
        stopButton.textContent = "STOP";
        stopButton.className = "btn btn-danger btn-sm text-white";

        const parent = startButton.parentElement;
        parent.replaceChild(stopButton, startButton);
        const taskName = input.value.trim();

        stopButton.addEventListener("click", async () => {
            clearInterval(timerInterval);
            stopButton.disabled = true;

            try {
                const response = await fetch("/timer/stop", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ name: taskName }),
                });
        
                const result = await response.json();
        
                if (!result.status) {
                    stopButton.disabled = false;
                    throw new Error(result.message);
                }

                input.value = "";
                timerDisplay.textContent = "00:00:00";
        
            } catch (error) {
                stopButton.disabled = false;
                console.error("Error stopping timer:", error);
                alert("Failed to stop timer. Please try again.");
            }        

            fetchTasks();
            parent.replaceChild(startButton, stopButton);
            startButton.disabled = false;
        });
    }

    function renderTasks(tasks) {
        taskContainer.innerHTML = "";

        let totalDailyDuration = 0;

        tasks.forEach(task => {
            const taskRow = document.createElement("div");
            taskRow.className = "p-3 border-top row g-0 align-items-center";

            let start, end, totalDuration = 0;
            if (task.entries.length > 0) {
                start = task.entries[0].start;
                end = task.entries[task.entries.length - 1].end || "Ongoing";

                totalDuration = task.entries.reduce((sum, entry) => {
                    return sum + (entry.duration || 0);
                }, 0);

                totalDailyDuration += totalDuration;
            }

            const badge = task.entries.length >= 2 
                ? `<span class="badge bg-info me-2" data-bs-toggle="collapse" data-bs-target="#task-${task.name.replace(/\s+/g, "-")}" aria-expanded="false" role="button">${task.entries.length}</span>` 
                : "";

            taskRow.innerHTML = `
                <div class="col-12 col-md-9 pb-5 pb-md-0 border-bottom border-md-0">
                    <p class="m-0">${badge} ${task.name}</p>
                </div>
                <div class="col-6 col-md-2 pt-2 pt-md-0 text-start text-md-center border-md-start">
                    <p class="m-0">${start || "N/A"} - ${end || "Ongoing"}</p>
                </div>
                <div class="col-6 col-md-1 pt-2 pt-md-0 text-end border-md-start">
                    <p class="m-0 lead">${formatDuration(totalDuration)}</p>
                </div>
            `;

            const collapseContainer = document.createElement("div");
            collapseContainer.id = `task-${task.name.replace(/\s+/g, "-")}`;
            collapseContainer.className = "collapse mt-2";

            task.entries.forEach(entry => {
                const entryRow = document.createElement("div");
                entryRow.className = "p-2 border-top row g-0 align-items-center";
                entryRow.innerHTML = `
                    <div class="col-6 text-start">
                        <p class="m-0 small">${entry.start} - ${entry.end || "Ongoing"}</p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="m-0 small">${formatDuration(entry.duration || 0)}</p>
                    </div>
                `;
                collapseContainer.appendChild(entryRow);
            });

            taskRow.appendChild(collapseContainer);
            taskContainer.appendChild(taskRow);
        });

        totalDisplay.textContent = formatDuration(totalDailyDuration);
    }

    function formatDuration(duration) {
        const hours = String(Math.floor(duration / 3600)).padStart(2, "0");
        const minutes = String(Math.floor((duration % 3600) / 60)).padStart(2, "0");
        const seconds = String(duration % 60).padStart(2, "0");
        return `${hours}:${minutes}:${seconds}`;
    }
});