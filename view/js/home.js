const strongStudents = document.getElementById("strongStudents");
const strongInstructor = document.getElementById("strongInstructor");
const strongRooms = document.getElementById("strongRooms");
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

const getDataRooms = async () => {
  const response = await fetch(`${ambientesAPI}.php?getCount=allRooms`);
  const result = await response.json();
  strongRooms.textContent = '';
  strongRooms.textContent = result.rooms;
}

const loadRenderStatistics = () => {
  getDataStudents();
  getDataInstructors();
  getDataDevices();
  getDataRooms();
}

window.addEventListener("DOMContentLoaded", loadRenderStatistics);