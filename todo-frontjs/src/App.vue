<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const toast = useToast();
const tasks = ref([]);
const importantTasks = ref([]);
const editing = ref(false);
const editTasksName = ref('');
const editTasksId = ref('');
const errorsAdd = ref(null);
const errorsEdit = ref(null);

const getTasks = () => {
  axios.get(`http://127.0.0.1:8000/api/task`)
    .then(response => {
      tasks.value = response.data.tasks;
      importantTasks.value = response.data.important;
    })
    .catch(error => {
      console.log(error);
    });
}

const addTask = () => {
  tasksName.value = document.getElementById('taskValue').value;
  axios.post(`http://127.0.0.1:8000/api/task`, {
    task_name: tasksName.value,
  })
    .then(function (response) {
      toast.success(response.data.message);
      tasksName.value = '';
      getTasks();
      errorsAdd.value = null;
      errorsEdit.value = null;
    })
    .catch(function (error) {
      errorsAdd.value = error.response.data.errors.task_name[0];
    });
}

const important = (taskId) => {
  const stringTaskId = String(taskId);
  axios.put(`http://127.0.0.1:8000/api/task/${stringTaskId}`)
    .then(function (response) {
      toast.success(response.data.message);
      getTasks();
    })
    .catch(function (error) {
      console.log(error);
    });
}

const markCompleted = (taskId: string) => {
  const stringTaskId = String(taskId);
  axios.put(`http://127.0.0.1:8000/api/completeTask/${stringTaskId}`)
    .then(function (response) {
      toast.success(response.data.message);
      getTasks();
    })
    .catch(function (error) {
      console.log(error);
    });
}

const deleteTasks = (taskId, taskName) => {
  const stringTaskId: string = String(taskId);
  swal.fire({
    title: "Are you sure you want to delete this tasks '"+ taskName + "' ?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete(`http://127.0.0.1:8000/api/task/${stringTaskId}`)
        .then(function (response) {
          toast.success(response.data.message);
          getTasks();
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  });

}

const edit = (taskId) => {
  const stringTaskId = String(taskId);
  axios.get(`http://127.0.0.1:8000/api/getEditTask/${stringTaskId}`)
    .then(function (response) {
      editTasksId.value = response.data.taskEditValues.tasks_id;
      editTasksName.value = response.data.taskEditValues.tasks_name;
      console.log(editTasksId.value);
    })
    .catch(function (error) {
      console.log(error);
    });
}

const closeEditModal = () => {
  editing.value = false;
}

const update = (taskId) => {
  const stringTaskId = String(taskId);
  axios.put(`http://127.0.0.1:8000/api/updateTask/${stringTaskId}`, {
    task_name: editTasksName.value,
  })
    .then(function (response) {
      toast.success(response.data.message);
      getTasks();
      errorsEdit.value = null;
      errorsAdd.value = null;
    })
    .catch(function (error) {
      errorsEdit.value = error.response.data.errors.task_name[0];
    });
}

onMounted(() => {
  getTasks();
});
</script>

<template>
  <header>
    <div class="p-1 text-center bg-body-tertiary">
      <h1 class="mb-3">To do App</h1>
      <h4 class="mb-3">by Graciano Asuncion</h4>
    </div>
  </header>

  <main>
    <section class="vh-100 gradient-custom-2">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">

          <div class="col-md-12 col-xl-10">

            <div class="card mask-custom">
              <div class="card-body p-4 text-white">
                <div class="input-group flex-nowrap">
                  <input type="text" id="taskValue" class="form-control" placeholder="Add new task"
                    aria-label="Username" aria-describedby="addon-wrapping" />
                  <button @click="addTask()" type="button" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                      <path
                        d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5">
                      </path>
                      <path
                        d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5M8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0">
                      </path>
                    </svg>
                  </button>
                </div>
                <div v-if="errorsAdd" class="alert alert-danger mt-2" role="alert">
                  {{ errorsAdd }}
                </div>

                <div class="pt-3 pb-2">
                  <h2 class="my-4 text-dark">Important</h2>
                </div>

                <table class="table text-white mb-0">
                  <thead>
                    <tr>
                      <th scople="cole"></th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody v-if="importantTasks.length > 0">
                    <tr v-for="(importantTask) in importantTasks" :key="importantTask.tasks_id" class="fw-normal">
                      <td class="align-middle">
                        <span class="badge text-bg-success" v-if="importantTask.completed == '1'">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-check2-circle" viewBox="0 0 16 16">
                            <path
                              d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                            <path
                              d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                          </svg>
                        </span>
                        <span class="badge text-bg-warning" v-else><svg xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" fill="currentColor" class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                            <path
                              d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                          </svg>
                        </span>
                      </td>
                      <td class="align-middle">
                        <span v-if="importantTask.completed == '1'"><s>{{ importantTask.tasks_name }}</s></span>
                        <span v-else>{{ importantTask.tasks_name }}</span>
                      </td>
                      <td class="align-middle">
                        <div class="row">
                          <div class="col">
                            <button @click="important(importantTask.tasks_id)" class="btn btn-light">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="yellow"
                                class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path
                                  d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                              </svg>
                            </button>
                          </div>
                          <div class="btn-group col">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              ...
                            </button>
                            <ul class="dropdown-menu">
                              <li><button type="button" @click="edit(importantTask.tasks_id)"
                                  class="btn btn-light dropdown-item" data-bs-toggle="modal"
                                  data-bs-target="#staticBackdrop">
                                  Edit Tasks
                                </button></li>
                              <li><button v-if="importantTask.completed == '1'"
                                  @click="markCompleted(importantTask.tasks_id)" class="btn btn-light dropdown-item"
                                  disabled>Mark as completed</button>
                                <button v-else @click="markCompleted(importantTask.tasks_id)"
                                  class="btn btn-light dropdown-item">Mark as completed</button>
                              </li>
                              <li><button @click="important(importantTask.tasks_id)"
                                  class="btn btn-light dropdown-item">Remove from importants</button></li>
                              <li><button @click="deleteTasks(importantTask.tasks_id, importantTask.tasks_name)"
                                  class="btn btn-light dropdown-item" href="#">Delete Tasks</button></li>
                            </ul>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td>No important tasks found</td>
                    </tr>
                  </tbody>
                </table>

                <div class="pt-3 pb-2">
                  <h2 class="my-4 text-dark">Tasks</h2>
                </div>

                <table class="table text-white mb-0">
                  <thead>
                    <tr>
                      <th scope="col"></th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody v-if="tasks.length > 0">
                    <tr v-for="(task) in tasks" :key="task.tasks_id" class="fw-normal">
                      <td class="align-middle">
                        <span class="badge text-bg-success" v-if="task.completed == '1'">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-check2-circle" viewBox="0 0 16 16">
                            <path
                              d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                            <path
                              d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                          </svg>
                        </span>
                        <span class="badge text-bg-warning" v-else><svg xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" fill="currentColor" class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                            <path
                              d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                          </svg>
                        </span>
                      </td>
                      <td class="align-middle">
                        <span v-if="task.completed == '1'"><s>{{ task.tasks_name }}</s></span>
                        <span v-else>{{ task.tasks_name }}</span>
                      </td>
                      <td class="align-middle">
                        <div class="row">
                          <div class="col">
                            <button @click="important(task.tasks_id)" class="btn btn-light">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="default"
                                class="bi bi-star" viewBox="0 0 16 16">
                                <path
                                  d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z" />
                              </svg>
                            </button>
                          </div>
                          <div class="btn-group col">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              ...
                            </button>
                            <ul class="dropdown-menu">
                              <li><button type="button" class="btn btn-light dropdown-item" @click="edit(task.tasks_id)"
                                  data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                  Edit Tasks
                                </button></li>
                              <li>
                                <button v-if="task.completed == '1'" @click="markCompleted(task.tasks_id)"
                                  class="btn btn-light dropdown-item" disabled>Mark
                                  as completed</button>

                                <button v-else @click="markCompleted(task.tasks_id)"
                                  class="btn btn-light dropdown-item">Mark
                                  as completed</button>

                              </li>
                              <li><button @click="important(task.tasks_id)" class="btn btn-light dropdown-item">Mark as
                                  important</button></li>
                              <li><button @click="deleteTasks(task.tasks_id, task.tasks_name)" class="btn btn-light dropdown-item">Delete
                                  Tasks</button></li>
                            </ul>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td>No tasks found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="update(editTasksId)">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Tasks Name</label>
                  <input type="text" v-model="editTasksName" class="form-control" id="tasksName" />
                  <div v-if="errorsEdit" class="alert alert-danger mt-2" role="alert">
                    {{ errorsEdit }}
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <button type="button" @click="closeEditModal()" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
