function validateDocument() {

    let documentType =
        document.getElementById("document_type").value;

    let documentFile =
        document.getElementById("document_file").files.length;


    let error =
        document.getElementById("js_error");


    error.innerHTML = "";

    error.style.display = "none";


    /* Check document type */

    if (documentType == "") {

        error.innerHTML =
            "Please select a document.";

        error.style.display = "block";

        return false;

    }


    /* Check file */

    if (documentFile == 0) {

        error.innerHTML =
            "Please select a file.";

        error.style.display = "block";

        return false;

    }


    return true;

}