const strongStudents = document.getElementById("strongStudents");
const strongInstructor = document.getElementById("strongInstructor");
const strongObs = document.getElementById("strongObs");
const strongDevices = document.getElementById("strongDevices");


const getDataStudents = async () => {
  const response = await fetch(`${usuariosAPI}.php?getByRole=students`);
  const result = await response.json();
  strongStudents.textContent = '';
  strongStudents.textContent = result.cantidad_usuarios;
}

const getDataInstructors = async () => {
  const response = await fetch(`${usuariosAPI}.php?getByRole=instructors`);
  const result = await response.json();
  strongInstructor.textContent = '';
  strongInstructor.textContent = result.cantidad_usuarios;
}

const getDataDevices = async () => {
  const response = await fetch(`${equiposAPI}.php?getCount=allDevices`);
  const result = await response.json();
  strongDevices.textContent = '';
  strongDevices.textContent = result.devices;
}

const getDataObs = async () => {
  const response = await fetch(`${observacionesAPI}.php?getCount=allObs`);
  const result = await response.json();
  strongObs.textContent = '';
  strongObs.textContent = result.observations;
}

const loadRenderStatistics = () => {
  getDataStudents();
  getDataInstructors();
  getDataDevices();
  getDataObs();
}

window.addEventListener("DOMContentLoaded", loadRenderStatistics);