function editGrade(id,value) {
    document.getElementById('ocene').value = JSON.stringify(id);
    document.getElementById('ocena').value = value;
    document.getElementById('formtitle').innerHTML="EDIT GRADE";
    var k = id.split("|");
    // 3. vrednost je grade_type
    gradeType = k[2];
    document.getElementById('gradetype').value=gradeType;
    // 4. vrednost je semestar_id
    semestar_id = k[3];
    document.getElementById('semestar').value=semestar_id;
    document.getElementById('addgrade').style.display= 'none' ;
    document.getElementById('updategrade').style.display= 'block' ;
    document.getElementById('deletegrade').style.display= 'block' ;
    document.getElementById('deletegrade').style.float= 'right' ;
}

function addGrade(id) {
    document.getElementById('ocene').value = JSON.stringify(id);
    document.getElementById('formtitle').innerHTML="ADD GRADE";
    document.getElementById('ocena').value = "";
    document.getElementById('addgrade').style.display= 'block' ;
    document.getElementById('updategrade').style.display= 'none' ;
    document.getElementById('deletegrade').style.display= 'none' ;
}