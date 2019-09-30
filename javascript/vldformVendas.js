function trocaClasse(elemento, antiga, nova) {
      elemento.classList.remove(antiga);
      elemento.classList.add(nova);
}

function vldForm() {
  var nomeCli = document.getElementById('nmCliente').txt;
  if nomeCli = "":
    var classe = document.getElementById('nmCliente');
    trocaClasse(classe, 'form-control', 'form-control is-invalid');

  else:
  var classe = document.getElementById('nmCliente');
  trocaClasse(classe, 'form-control', 'form-control is-valid');

}
