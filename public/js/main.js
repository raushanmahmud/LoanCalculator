const loans = document.getElementById('loans');

if (loans){
    loans.addEventListener('click',(e)=>{
        if (e.target.className==='btn btn-danger delete-loan'){
            if (confirm('Are you sure?')){
                const id = e.target.getAttribute('data-id');
                fetch(`/loan/delete/${id}`,{
                    method : 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    })
}