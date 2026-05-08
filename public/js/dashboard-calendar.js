initDashboardCalendar();

function initDashboardCalendar() {
    const calendarElement = document.getElementById('calender');

    if (!calendarElement) {
        return;
    }

    const currentLabel = document.getElementById('calendar-current-label');
    const weekdaysRow = document.getElementById('calendar-weekdays');
    const calendarGrid = document.getElementById('calendar-grid');
    const prevButton = document.getElementById('calendar-prev-button');
    const nextButton = document.getElementById('calendar-next-button');
    const todayButton = document.getElementById('calendar-today-button');
    const leadsPanelTitle = document.getElementById('calendar-leads-title');
    const leadsPanelEmpty = document.getElementById('calendar-leads-empty');
    const leadsPanelList = document.getElementById('calendar-leads-list');

    if (!currentLabel || !weekdaysRow || !calendarGrid || !prevButton || !nextButton || !todayButton) {
        return;
    }

    let leadsByDate = {};

    try {
        leadsByDate = JSON.parse(calendarElement.dataset.leads || '{}');
    } catch (error) {
        leadsByDate = {};
    }

    const today = new Date();
    const todayKey = formatDateKey(today);
    let viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
    let selectedDateKey = todayKey;

    renderWeekdays();
    renderCalendar();
    renderLeadsPanel(selectedDateKey);

    prevButton.addEventListener('click', function () {
        viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() - 1, 1);
        renderCalendar();
    });

    nextButton.addEventListener('click', function () {
        viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 1);
        renderCalendar();
    });

    todayButton.addEventListener('click', function () {
        viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
        selectedDateKey = todayKey;
        renderCalendar();
        renderLeadsPanel(selectedDateKey);
    });

    function renderWeekdays() {
        const weekdayLabels = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

        weekdaysRow.innerHTML = '';

        weekdayLabels.forEach(function (label) {
            const cell = document.createElement('div');
            cell.className = 'calendar-weekday';
            cell.textContent = label;
            weekdaysRow.appendChild(cell);
        });
    }

    function renderCalendar() {
        currentLabel.textContent = formatMonthLabel(viewDate);
        calendarGrid.innerHTML = '';

        const firstDayIndex = getMondayBasedDayIndex(viewDate);
        const gridStartDate = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1 - firstDayIndex);

        for (let index = 0; index < 42; index += 1) {
            const cellDate = new Date(gridStartDate.getFullYear(), gridStartDate.getMonth(), gridStartDate.getDate() + index);
            const cellKey = formatDateKey(cellDate);
            const leadEntry = leadsByDate[cellKey] || null;
            const leadsCount = Number(leadEntry?.count || 0);
            const isCurrentMonth = cellDate.getMonth() === viewDate.getMonth();
            const isToday = cellKey === todayKey;
            const isSelected = cellKey === selectedDateKey;

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'calendar-day';
            button.dataset.day = cellKey;

            if (!isCurrentMonth) {
                button.classList.add('is-outside-month');
            }

            if (isToday) {
                button.classList.add('is-today');
            }

            if (isSelected) {
                button.classList.add('is-selected');
            }

            if (leadsCount > 0) {
                button.classList.add('has-leads');
                button.dataset.leadsCount = String(leadsCount);
                button.title = 'Лидов: ' + leadsCount;
            }

            button.innerHTML = '<span class="calendar-day-number">' + cellDate.getDate() + '</span>';
            button.addEventListener('click', function () {
                selectedDateKey = cellKey;

                if (!isCurrentMonth) {
                    viewDate = new Date(cellDate.getFullYear(), cellDate.getMonth(), 1);
                }

                renderCalendar();
                renderLeadsPanel(selectedDateKey);
            });

            calendarGrid.appendChild(button);
        }
    }

    function renderLeadsPanel(dayKey) {
        if (!leadsPanelTitle || !leadsPanelEmpty || !leadsPanelList) {
            return;
        }

        const leadEntry = leadsByDate[dayKey] || null;
        const leadItems = Array.isArray(leadEntry?.items) ? leadEntry.items : [];

        leadsPanelTitle.textContent = 'Лиды за ' + formatDisplayDate(dayKey);
        leadsPanelList.innerHTML = '';

        if (!leadItems.length) {
            leadsPanelEmpty.textContent = 'За этот день заявок нет.';
            leadsPanelEmpty.classList.remove('hidden');
            leadsPanelList.classList.add('hidden');
            return;
        }

        leadItems.forEach(function (lead) {
            const item = document.createElement('li');
            item.className = 'flex items-center justify-between rounded-lg border border-white/10 bg-black/20 px-3 py-2';
            item.innerHTML = '<span class="text-sm font-medium text-gray-200">' + escapeHtml(String(lead.time || '--:--')) + '</span>'
                + '<span class="rounded-full bg-orange-500/15 px-2.5 py-1 text-xs font-medium text-orange-300">'
                + escapeHtml(getLeadStatusLabel(lead.status))
                + '</span>';
            leadsPanelList.appendChild(item);
        });

        leadsPanelEmpty.classList.add('hidden');
        leadsPanelList.classList.remove('hidden');
    }
}

function getLeadStatusLabel(status) {
    switch (status) {
        case 'new':
            return 'Новая';
        case 'in_progress':
            return 'В работе';
        case 'done':
            return 'Завершена';
        case 'spam':
            return 'Спам';
        default:
            return status || 'Без статуса';
    }
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function formatDateKey(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return year + '-' + month + '-' + day;
}

function formatDisplayDate(dayKey) {
    const [year, month, day] = dayKey.split('-');
    return [day, month, year].join('.');
}

function formatMonthLabel(date) {
    const monthNames = [
        'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
    ];

    return monthNames[date.getMonth()] + ' ' + date.getFullYear();
}

function getMondayBasedDayIndex(date) {
    const sundayBasedIndex = date.getDay();
    return sundayBasedIndex === 0 ? 6 : sundayBasedIndex - 1;
}
