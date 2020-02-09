var clyIdFromLocalStorage = localStorage.getItem("cly_id");
if (clyIdFromLocalStorage !== null) {
    document.cookie = "cly_id=" + clyIdFromLocalStorage
}