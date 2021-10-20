function mockData(fields){
  window.addEventListener('DOMContentLoaded', (event) => fields && Object.keys(fields).forEach((key)=> document.body.innerHTML = document.body.innerHTML.replace(new RegExp('{{'+ key +'}}', 'g'), fields[key])));
}
