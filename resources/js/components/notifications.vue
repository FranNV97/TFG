<template>
  <div class="container-fluid row justify-content-center">
    <div class="modal fade" id="modalNotification" role="dialog">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4
              v-if="(notificationModal.typeElement == 1 || notificationModal.typeElement == 4) && notificationModal.objeto.type == 1"
              class="modal-title col-md-8"
            >Incidencia: {{ notificationModal.objeto.name }}</h4>
            <h4
              v-else-if="(notificationModal.typeElement == 1 || notificationModal.typeElement == 4) && notificationModal.objeto.type == 2"
              class="modal-title col-md-8"
            >Problema: {{ notificationModal.objeto.name }}</h4>
            <h4
              v-else-if="notificationModal.typeElement == 2"
              class="modal-title col-md-8"
            >Proceso: {{ notificationModal.objeto.name }}</h4>
            <h4
              v-else-if="notificationModal.typeElement == 3"
              class="modal-title col-md-8"
            >Análisis de valor añadido de {{ process.name }}</h4>

            <div class="col-md-4 row justify-content-end">
              <button
                class="btn btn-primary pocoMargenDerecha textoTable"
                v-if="notificationModal.typeElement == 1"
                @click="aprobarNotificacion()"
                data-dismiss="modal"
              >Aprobar</button>
              <button
                class="btn btn-danger pocoMargenDerecha textoTable"
                v-if="notificationModal.typeElement == 1"
                @click="denegarNotificacion()"
                data-dismiss="modal"
              >Denegar</button>
              <button
                class="btn btn-danger pocoMargenDerecha textoTable"
                v-if="notificationModal.typeElement > 1"
                @click="eliminarNotificacionModal()"
                data-dismiss="modal"
              >Eliminar notificación</button>
            </div>
          </div>

          <div class="modal-body col-md-12">
            <div v-if="notificationModal.typeElement == 1 || notificationModal.typeElement == 4">
              <p class="textoTable">
                <b>Proceso</b>
              </p>
              <p class="textoTable">{{ process.name }}</p>

              <p
                class="textoTable"
                v-if="notificationModal.objeto.description != null && notificationModal.objeto.description != ''"
              >
                <b>Descripción</b>
              </p>
              <p
                class="textoTable"
                style="white-space: pre-line;"
              >{{ notificationModal.objeto.description }}</p>

              <p
                class="textoTable"
                v-if="notificationModal.objeto.selected != undefined && notificationModal.objeto.selected.length > 0"
              >
                <b>Tareas afectadas</b>
              </p>
              <ul
                class="pocoMargenBajo"
                v-if="notificationModal.objeto.selected != undefined && notificationModal.objeto.selected.length > 0"
              >
                <li v-for="task in notificationModal.objeto.selected" :key="task.id">
                  <p class="textoTable">{{ task.name }}</p>
                </li>
              </ul>

              <p
                class="textoTable"
                v-if="notificationModal.objeto.recorte != null && notificationModal.objeto.recorte != ''"
              >
                <b>Imagen de la parte afectada</b>
              </p>
              <img
                v-if="notificationModal.objeto.recorte != null && notificationModal.objeto.recorte != ''"
                :src="notificationModal.objeto.recorte"
              />
            </div>
            <div v-else-if="notificationModal.typeElement == 2">
              <div
                class="form-group"
                v-if="notificationModal.objeto.description != null && notificationModal.objeto.description != ''"
              >
                <label class="textoTable">
                  <b>Descripción</b>
                </label>
                <p
                  class="textoTable"
                  style="white-space: pre-line;"
                >{{ notificationModal.objeto.description }}</p>
              </div>

              <div
                class="pocoMargenBajo"
                v-if="notificationModal.objeto.tasksProcessModal != undefined && notificationModal.objeto.tasksProcessModal.length > 0"
              >
                <label class="textoTable">
                  <b>Tareas</b>
                </label>
              </div>
              <table
                class="table table-striped"
                v-if="notificationModal.objeto.tasksProcessModal != undefined && notificationModal.objeto.tasksProcessModal.length > 0"
              >
                <thead class="thead-dark">
                  <tr>
                    <th class="textoCabeceraTable" style="width: 70%">Nombre</th>
                    <th class="textoCabeceraTable" style="width: 30%">Tipo</th>
                  </tr>
                </thead>

                <tbody>
                  <tr
                    v-for="(task, index) in notificationModal.objeto.tasksProcessModal"
                    :key="index"
                  >
                    <td>
                      <p class="textoTable">{{ task.name }}</p>
                    </td>
                    <td>
                      <p class="textoTable" v-if="task.type == 1">Servicio</p>
                      <p class="textoTable" v-else-if="task.type == 2">Subproceso</p>
                      <p class="textoTable" v-else-if="task.type == 3">Recepción</p>
                      <p class="textoTable" v-else-if="task.type == 4">Envío</p>
                      <p class="textoTable" v-else-if="task.type == 5">Usuario</p>
                      <p class="textoTable" v-else-if="task.type == 6">Secuencia de comandos</p>
                      <p class="textoTable" v-else-if="task.type == 7">Abstracta</p>
                      <p class="textoTable" v-else>--</p>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div
                class="pocoMargenBajo"
                v-if="notificationModal.objeto.documentationsProcessModal != undefined && notificationModal.objeto.documentationsProcessModal.length > 0"
              >
                <label class="textoTable">
                  <b>Documentación</b>
                </label>
              </div>
              <table
                class="table table-striped"
                v-if="notificationModal.objeto.documentationsProcessModal != undefined && notificationModal.objeto.documentationsProcessModal.length > 0"
              >
                <thead class="thead-dark">
                  <tr>
                    <th class="textoCabeceraTable" style="width: 70%">Nombre</th>
                    <th class="textoCabeceraTable" style="width: 30%">Tipo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="doc in notificationModal.objeto.documentationsProcessModal"
                    :key="doc.id"
                  >
                    <td>
                      <p class="textoTable">{{ doc.name }}</p>
                    </td>
                    <td>
                      <p class="textoTable" v-if="doc.type == 1">General</p>
                      <p class="textoTable" v-else-if="doc.type == 2">Imagen BPMN</p>
                      <p class="textoTable" v-else-if="doc.type == 3">Archivo BPMN</p>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div
                class="pocoMargenBajo"
                v-if="notificationModal.objeto.participantesProcessModal != undefined && notificationModal.objeto.participantesProcessModal.length > 0"
              >
                <label class="textoTable">
                  <b>Participantes</b>
                </label>
              </div>
              <table
                class="table table-striped"
                v-if="notificationModal.objeto.participantesProcessModal != undefined && notificationModal.objeto.participantesProcessModal.length > 0"
              >
                <thead class="thead-dark">
                  <tr>
                    <th class="textoCabeceraTable" style="width: 40%">Nombre</th>
                    <th class="textoCabeceraTable" style="width: 35%">Correo</th>
                    <th class="textoCabeceraTable" style="width: 25%">Rol</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="participante in notificationModal.objeto.participantesProcessModal"
                    :key="participante.id"
                  >
                    <td>
                      <p class="textoTable">{{ participante.name }} {{ participante.surname }}</p>
                    </td>
                    <td>
                      <p class="textoTable">{{ participante.email }}</p>
                    </td>
                    <td>
                      <p class="textoTable" v-if="participante.role == 1">Actor</p>
                      <p class="textoTable" v-else-if="participante.role == 2">Responsable</p>
                      <p class="textoTable" v-else-if="participante.role == 3">Análisis</p>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div
                class="pocoMargenBajo"
                v-if="notificationModal.objeto.objectivesProcessModal != undefined && notificationModal.objeto.objectivesProcessModal.length > 0"
              >
                <label class="textoTable">
                  <b>Objetivos</b>
                </label>
              </div>
              <table
                class="table table-striped"
                v-if="notificationModal.objeto.objectivesProcessModal != undefined && notificationModal.objeto.objectivesProcessModal.length > 0"
              >
                <thead class="thead-dark">
                  <tr>
                    <th class="textoCabeceraTable" style="width: 60%">Nombre</th>
                    <th class="textoCabeceraTable" style="width: 20%">Valor</th>
                    <th class="textoCabeceraTable" style="width: 20%">Tipo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="objetivo in notificationModal.objeto.objectivesProcessModal"
                    :key="objetivo.id"
                  >
                    <td>
                      <p class="textoTable">{{ objetivo.name }}</p>
                    </td>
                    <td>
                      <p class="textoTable">{{ objetivo.valor }}</p>
                    </td>
                    <td>
                      <p class="textoTable" v-if="objetivo.type == 1">Ventas</p>
                      <p class="textoTable" v-else-if="objetivo.type == 2">Redes sociales</p>
                      <p class="textoTable" v-else-if="objetivo.type == 3">Retail</p>
                      <p class="textoTable" v-else-if="objetivo.type == 4">Logística</p>
                      <p class="textoTable" v-else-if="objetivo.type == 5">Producción</p>
                      <p class="textoTable" v-else-if="objetivo.type == 6">Financiero</p>
                      <p class="textoTable" v-else-if="objetivo.type == 7">Marketing digital</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-if="notificationModal.typeElement == 3">
              <p class="textoTable">
                <b>Tareas</b>
              </p>
              <table class="table table-striped">
                <thead class="thead-dark">
                  <tr>
                    <th class="textoCabeceraTable" style="width: 40%">Nombre</th>
                    <th class="textoCabeceraTable" style="width: 30%">Valor análisis</th>
                    <th class="textoCabeceraTable" style="width: 30%">Razón desperdicio</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="task in notificationModal.objeto.tareasAnalisis" :key="task.tarea.id">
                    <td v-if="task.analisis.name != ''">
                      <p class="textoTable">{{ task.analisis.name }}</p>
                    </td>
                    <td v-else>
                      <p class="textoTable">{{ task.tarea.name }}</p>
                    </td>

                    <td v-if="task.analisis.value != ''">
                      <p
                        v-if="task.analisis.value == 1"
                        class="pocoMargenBajo textoTable"
                      >Valor añadido</p>
                      <p
                        v-else-if="task.analisis.value == 2"
                        class="pocoMargenBajo textoTable"
                      >Valor añadido para negocio</p>
                      <p v-else class="pocoMargenBajo textoTable">Sin valor añadido</p>

                      <div v-if="task.analisis.reason != null && task.analisis.reason != ''">
                        <p class="textoTable">
                          <b>Razón</b>
                        </p>
                        <p
                          class="textoTable"
                          style="white-space: pre-line;"
                        >{{ task.analisis.reason }}</p>
                      </div>
                    </td>
                    <td v-else>
                      <p class="textoTable">--</p>
                    </td>
                    <td>
                      <ul class="list-group pocoMargenBajo" v-if="task.analisis.value == 3">
                        <li
                          class="list-group-item d-flex justify-content-between align-items-center"
                          v-for="(waste, indexWaste) in task.analisis.wastes"
                          :key="indexWaste"
                        >
                          <p class="textoTable" v-if="waste.type != null && waste.type == 1">Defecto</p>
                          <p
                            class="textoTable"
                            v-else-if="waste.type != null && waste.type == 2"
                          >Espera</p>
                          <p
                            class="textoTable"
                            v-else-if="waste.type != null && waste.type == 3"
                          >Talento no utilizado</p>
                          <p
                            class="textoTable"
                            v-else-if="waste.type != null && waste.type == 4"
                          >Transporte</p>
                          <p
                            class="textoTable"
                            v-else-if="waste.type != null && waste.type == 5"
                          >Inventario</p>
                          <p
                            class="textoTable"
                            v-else-if="waste.type != null && waste.type == 6"
                          >Movimiento</p>
                          <p
                            class="textoTable"
                            v-else-if="waste.type != null && waste.type == 7"
                          >Procesamiento adicional</p>
                          <p class="textoTable" v-else-if="waste.name != null">{{ waste.name }}</p>
                        </li>
                      </ul>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-10">
      <h1 class="col-md-4 pocoMargenBajo">Notificaciones</h1>
      <div class="row">
        <div
          class="card col-md-3 pocoMargen"
          v-for="(notification, index) in notifications"
          :key="notification.id"
        >
          <div class="card-body">
            <div class="row justify-content-between">
              <h5 class="card-title col-md-10">{{ notification.message }}</h5>

              <div class="row">
                <button
                  class="btn sinBorde"
                  data-toggle="modal"
                  data-target="#modalNotification"
                  @click="assignDataNotification(index)"
                >
                  <i class="fa fa-arrows-alt fa-lg" aria-hidden="true"></i>
                </button>

                <button
                  class="btn sinBorde"
                  @click="eliminarNotificacion(index)"
                  v-if="notification.typeElement > 1"
                >
                  <i class="fa fa-times fa-lg" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["usuario", "notificaciones"],

  data() {
    return {
      user: {},
      notifications: [],
      notificationModal: {
        id: "",
        message: "",
        typeElement: "",
        objeto: {},
        index: "",
      },
      process: {},
    };
  },

  methods: {
    assignDataNotification(index) {
      let notificacion = this.$data.notifications[index];
      let objeto = {};

      this.$data.notificationModal.id = notificacion.id;
      this.$data.notificationModal.message = notificacion.message;
      this.$data.notificationModal.typeElement = notificacion.typeElement;
      this.$data.notificationModal.index = index;

      if (notificacion.typeElement == 1) {
        axios.get("/issues/" + notificacion.idElement).then((response) => {
          objeto = response.data;
          objeto.selected = [];

          axios.get("/processes/bean/" + objeto.process_id).then((response) => {
            this.$data.process = response.data;
          });

          axios
            .get("/issues/" + notificacion.idElement + "/tasks")
            .then((response) => {
              objeto.selected = response.data;
            });

          this.$data.notificationModal.objeto = objeto;
        });
      } else if (notificacion.typeElement == 2) {
        axios
          .get("/processes/bean/" + notificacion.idElement)
          .then((response) => {
            objeto = response.data;

            objeto = response.data;
            objeto.tasksProcessModal = [];
            objeto.documentationsProcessModal = [];
            objeto.participantesProcessModal = [];
            objeto.objectivesProcessModal = [];

            axios
              .get("/tasks/process/" + notificacion.idElement)
              .then((response) => {
                objeto.tasksProcessModal = response.data;
              });

            axios
              .get("/documentations/process/" + notificacion.idElement)
              .then((response) => {
                objeto.documentationsProcessModal = response.data;
              });

            axios
              .get("/users/process/" + notificacion.idElement)
              .then((response) => {
                objeto.participantesProcessModal = response.data;
              });

            axios
              .get("/objectives/process/" + notificacion.idElement)
              .then((response) => {
                objeto.objectivesProcessModal = response.data;
              });

            this.$data.notificationModal.objeto = objeto;
          });
      } else if (notificacion.typeElement == 3) {
        axios.get("/analysis/" + notificacion.idElement).then((response) => {
          objeto = response.data;

          axios.get("/processes/bean/" + objeto.process_id).then((response) => {
            this.$data.process = response.data;
          });

          objeto.tareasAnalisis = [];

          axios.get("/tasks/process/" + objeto.process_id).then((response) => {
            let totalTareas = response.data;

            totalTareas.forEach((element) => {
              let tareaAnalisis = {
                tarea: element,
                analisis: {
                  id: "",
                  name: "",
                  process_id: this.$data.process.id,
                  value: "",
                  task_id: element.id,
                  reason: "",
                  wastes: [],
                  newWastes: [],
                  deleteWastes: [],
                },
              };

              objeto.tareasAnalisis.push(tareaAnalisis);
            });

            axios.get("/analysis/" + objeto.id + "/tasks").then((response) => {
              let tareasAnalisis = response.data;

              tareasAnalisis.forEach((elementAnalisis) => {
                let aparece = false;

                axios
                  .get("/analysis/wastes/" + elementAnalisis.id)
                  .then((res) => {
                    let wastes = res.data;

                    objeto.tareasAnalisis.forEach((elementTarea) => {
                      if (elementAnalisis.task_id == elementTarea.tarea.id) {
                        aparece = true;

                        elementTarea.analisis.id = elementAnalisis.id;
                        elementTarea.analisis.name = elementAnalisis.name;
                        elementTarea.analisis.process_id =
                          elementAnalisis.process_id;
                        elementTarea.analisis.value = elementAnalisis.value;
                        elementTarea.analisis.task_id = elementAnalisis.task_id;
                        elementTarea.analisis.reason = elementAnalisis.reason;
                        elementTarea.analisis.wastes = wastes;
                        elementTarea.analisis.newWastes = [];
                        elementTarea.analisis.deleteWastes = [];
                        elementTarea.analisis.typeWaste = 1;
                        elementTarea.analisis.wasteTypeValue = 1;
                        elementTarea.analisis.wasteTextValue = "";
                      }
                    });

                    if (!aparece) {
                      let tareaAnalisis = {
                        tarea: {},
                        analisis: {
                          id: elementAnalisis.id,
                          name: elementAnalisis.name,
                          process_id: elementAnalisis.process_id,
                          value: elementAnalisis.value,
                          task_id: elementAnalisis.task_id,
                          reason: elementAnalisis.reason,
                          wastes: wastes,
                          newWastes: [],
                          deleteWastes: [],
                          typeWaste: 1,
                          wasteTypeValue: 1,
                          wasteTextValue: "",
                        },
                      };

                      objeto.tareasAnalisis.push(tareaAnalisis);
                    }
                  });
              });
            });
          });

          this.$data.notificationModal.objeto = objeto;
        });
      } else if (notificacion.typeElement == 4) {
        axios.get("/issues/" + notificacion.idElement).then((response) => {
          objeto = response.data;

          axios.get("/processes/bean/" + objeto.process_id).then((response) => {
            this.$data.process = response.data;
          });

          axios
            .get("/issues/" + notificacion.idElement + "/tasks")
            .then((response) => {
              objeto.selected = response.data;
            });

          this.$data.notificationModal.objeto = objeto;
        });
      }
    },

    aprobarNotificacion() {
      let data = {
        type: 2,
        state: 3,
        modificado: this.$data.user.id,
      };

      axios
        .put(
          "/issues/" + this.$data.notificationModal.objeto.id,
          data
        )
        .then((response) => {
          let typeDelete = {
            typeElement: this.$data.notificationModal.typeElement,
            idElement: this.$data.notificationModal.objeto.id,
          };

          axios
            .post("/notifications/sameType", typeDelete)
            .then((response) => {
              this.$data.notifications.splice(
                this.$data.notificationModal.index,
                1
              );
            })
            .catch((error) => {
              console.log(error.response);
            });

          axios
            .get(
              "/users/process/" + this.$data.notificationModal.objeto.process_id
            )
            .then((response) => {
              let responsables = response.data.filter(
                (u) => u.role == 1 || u.role == 3
              );

              responsables.forEach((element) => {
                let notification = {
                  message:
                    "Se ha aprobado el paso a problema de la incidencia " +
                    this.$data.notificationModal.objeto.name,
                  typeElement: 4,
                  idElement: this.$data.notificationModal.objeto.id,
                  user_id: element.id,
                };

                axios
                  .post("/notifications", notification)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            });
        })
        .catch((error) => {
          console.log(error.response);
        });
    },

    denegarNotificacion() {
      let data = {
        state: -1,
      };

      axios
        .put(
          "/issues/" + this.$data.notificationModal.objeto.id,
          data
        )
        .then((response) => {
          let typeDelete = {
            typeElement: this.$data.notificationModal.typeElement,
            idElement: this.$data.notificationModal.objeto.id,
          };

          axios
            .post("/notifications/sameType", typeDelete)
            .then((response) => {
              this.$data.notifications.splice(
                this.$data.notificationModal.index,
                1
              );
            })
            .catch((error) => {
              console.log(error.response);
            });

          axios
            .get(
              "/users/process/" + this.$data.notificationModal.objeto.process_id
            )
            .then((response) => {
              let responsables = response.data.filter(
                (u) => u.role == 1 || u.role == 3
              );

              responsables.forEach((element) => {
                let notification = {
                  message:
                    "Se ha rechazado el paso a problema de la incidencia " +
                    this.$data.notificationModal.objeto.name,
                  typeElement: 4,
                  idElement: this.$data.notificationModal.objeto.id,
                  user_id: element.id,
                };

                axios
                  .post("/notifications", notification)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            });
        })
        .catch((error) => {
          console.log(error.response);
        });
    },

    eliminarNotificacion(index) {
      let notificacion = this.$data.notifications[index];

      axios
        .delete("/notifications/" + notificacion.id)
        .then((response) => {
          this.$data.notifications.splice(index, 1);
        })
        .catch((error) => {
          console.log(error.response);
        });
    },

    eliminarNotificacionModal() {
      axios
        .delete("/notifications/" + this.$data.notificationModal.id)
        .then((response) => {
          this.$data.notifications.splice(
            this.$data.notificationModal.index,
            1
          );
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
  },

  mounted() {
    this.user = this.usuario;
    this.notifications = this.notificaciones;
  },
};
</script>

<style>
</style>
