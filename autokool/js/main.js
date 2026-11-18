// js/main.js - Kliendipoolsed skriptid

document.addEventListener('DOMContentLoaded', () => {
    // Kursusele registreerumise nuppude kuulajad
    const enrollButtons = document.querySelectorAll('.open-enroll-modal');
    const modal = document.getElementById('enrollModal');
    const courseIdInput = document.getElementById('modalCourseId');
    const courseTitleEl = document.getElementById('modalCourseTitle');

    enrollButtons.forEach(button => {
        button.addEventListener('click', () => {
            const courseId = button.getAttribute('data-id');
            const courseTitle = button.getAttribute('data-title');

            if (courseIdInput) courseIdInput.value = courseId;
            if (courseTitleEl) courseTitleEl.textContent = `Registreerumine: ${courseTitle}`;
            if (modal) modal.style.display = 'flex';
        });
    });

    // Modaalakna sulgemine taustale klikkides
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
});

function closeModal() {
    const modal = document.getElementById('enrollModal');
    if (modal) modal.style.display = 'none';
}

function toggleAddCourseForm() {
    const box = document.getElementById('addCourseBox');
    if (box) {
        box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
        if (box.style.display === 'block') {
            box.scrollIntoView({ behavior: 'smooth' });
        }
    }
}
