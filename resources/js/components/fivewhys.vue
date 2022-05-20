<template>
  <div class="container-fluid row justify-content-center">
    <div class="col-md-10">
      <div class="row justify-content-between">
        <h1 class="col-md-4 pocoMargenBajo">{{ issue.name }}</h1>

        <div class="col-md-8">
          <div class="row justify-content-end">
            <button class="btn btn-outline-dark btn-lg" @click="volverProblemas()">Volver</button>
          </div>
        </div>
      </div>
      <div class="row">
        <table class="table table-light table-striped">
          <thead class="thead-dark">
            <tr>
              <th class="textoCabeceraTable" style="width: 20%">Nivel 1</th>
              <th class="textoCabeceraTable" style="width: 20%">Nivel 2</th>
              <th class="textoCabeceraTable" style="width: 20%">Nivel 3</th>
              <th class="textoCabeceraTable" style="width: 20%">Nivel 4</th>
              <th class="textoCabeceraTable" style="width: 20%">Nivel 5</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, index) in rows" :key="index">
              <td v-if="row.level == 1">
                <button
                  class="btn btn-outline-success textoTable"
                  v-if="!row.nuevo && row.answer == ''"
                  @click="crearRespuestaMismoNivel(index)"
                >
                  Nueva respuesta del mismo nivel
                  <i class="fa fa-arrow-down fa-lg" aria-hidden="true"></i>
                </button>
                <div v-else-if="!row.nuevo">
                  <p class="textoTable">{{ row.answer }}</p>
                  <div class="form-check" v-if="!tieneHijos(index)">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      disabled
                      v-if="row.rootCause == true"
                    />
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      @change="marcarCausaRaiz(index)"
                      v-else
                    />
                    <label class="form-check-label textoTable" :for="'check' + index">Es causa raíz</label>
                  </div>
                </div>
                <div v-else>
                  <input
                    type="text"
                    class="form-control pocoMargenBajo textoTable"
                    placeholder="Respuesta"
                    v-model="row.answer"
                  />
                  <button class="btn btn-primary textoTable" @click="confirmarRespuesta(index)">Confirmar</button>
                  <button
                    class="btn btn-outline-danger textoTable"
                    @click="cancelarRespuesta(index)"
                    v-if="rows.length > 1"
                  >Cancelar</button>
                </div>
              </td>
              <td v-else></td>
              <td v-if="row.level == 2">
                <button
                  class="btn btn-outline-success textoTable"
                  v-if="!row.nuevo && row.answer == ''"
                  @click="crearRespuestaMismoNivel(index)"
                >
                  Nueva respuesta del mismo nivel
                  <i class="fa fa-arrow-down fa-lg" aria-hidden="true"></i>
                </button>
                <div v-else-if="!row.nuevo">
                  <p class="textoTable">{{ row.answer }}</p>
                  <div class="form-check" v-if="!tieneHijos(index)">
                    <input
                      type="checkbox"
                      class="form-check-input textoTable"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      disabled
                      v-if="row.rootCause == true"
                    />
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      @change="marcarCausaRaiz(index)"
                      v-else
                    />
                    <label class="form-check-label textoTable" :for="'check' + index">Es causa raíz</label>
                  </div>
                </div>
                <div v-else>
                  <input
                    type="text"
                    class="form-control pocoMargenBajo textoTable"
                    placeholder="Respuesta"
                    v-model="row.answer"
                  />
                  <button class="btn btn-primary textoTable" @click="confirmarRespuesta(index)">Confirmar</button>
                  <button
                    class="btn btn-outline-danger textoTable"
                    @click="cancelarRespuesta(index)"
                    v-if="rows.length > 1"
                  >Cancelar</button>
                </div>
              </td>
              <td
                v-else-if="row.level == 1 && !row.nuevo && row.answer != '' && !tieneHijos(index)"
              >
                <button class="btn btn-outline-success textoTable" v-if="row.rootCause == false" @click="crearRespuestaNivelInferior(index)">
                  Nueva respuesta de un nivel inferior
                  <i
                    class="fa fa-arrow-right fa-lg"
                    aria-hidden="true"
                  ></i>
                </button>
              </td>
              <td v-else></td>
              <td v-if="row.level == 3">
                <button
                  class="btn btn-outline-success textoTable"
                  v-if="!row.nuevo && row.answer == ''"
                  @click="crearRespuestaMismoNivel(index)"
                >
                  Nueva respuesta del mismo nivel
                  <i class="fa fa-arrow-down fa-lg" aria-hidden="true"></i>
                </button>
                <div v-else-if="!row.nuevo">
                  <p class="textoTable">{{ row.answer }}</p>
                  <div class="form-check" v-if="!tieneHijos(index)">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      disabled
                      v-if="row.rootCause == true"
                    />
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      @change="marcarCausaRaiz(index)"
                      v-else
                    />
                    <label class="form-check-label textoTable" :for="'check' + index">Es causa raíz</label>
                  </div>
                </div>
                <div v-else>
                  <input
                    type="text"
                    class="form-control pocoMargenBajo"
                    placeholder="Respuesta"
                    v-model="row.answer"
                  />
                  <button class="btn btn-primary textoTable" @click="confirmarRespuesta(index)">Confirmar</button>
                  <button
                    class="btn btn-outline-danger textoTable"
                    @click="cancelarRespuesta(index)"
                    v-if="rows.length > 1"
                  >Cancelar</button>
                </div>
              </td>
              <td
                v-else-if="row.level == 2 && !row.nuevo && row.answer != '' && !tieneHijos(index)"
              >
                <button class="btn btn-outline-success textoTable" v-if="row.rootCause == false" @click="crearRespuestaNivelInferior(index)">
                  Nueva respuesta de un nivel inferior
                  <i
                    class="fa fa-arrow-right fa-lg"
                    aria-hidden="true"
                  ></i>
                </button>
              </td>
              <td v-else></td>
              <td v-if="row.level == 4">
                <button
                  class="btn btn-outline-success textoTable"
                  v-if="!row.nuevo && row.answer == ''"
                  @click="crearRespuestaMismoNivel(index)"
                >
                  Nueva respuesta del mismo nivel
                  <i class="fa fa-arrow-down fa-lg" aria-hidden="true"></i>
                </button>
                <div v-else-if="!row.nuevo">
                  <p class="textoTable">{{ row.answer }}</p>
                  <div class="form-check" v-if="!tieneHijos(index)">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      disabled
                      v-if="row.rootCause == true"
                    />
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      @change="marcarCausaRaiz(index)"
                      v-else
                    />
                    <label class="form-check-label textoTable" :for="'check' + index">Es causa raíz</label>
                  </div>
                </div>
                <div v-else>
                  <input
                    type="text"
                    class="form-control pocoMargenBajo textoTable"
                    placeholder="Respuesta"
                    v-model="row.answer"
                  />
                  <button class="btn btn-primary textoTable" @click="confirmarRespuesta(index)">Confirmar</button>
                  <button
                    class="btn btn-outline-danger textoTable"
                    @click="cancelarRespuesta(index)"
                    v-if="rows.length > 1"
                  >Cancelar</button>
                </div>
              </td>
              <td
                v-else-if="row.level == 3 && !row.nuevo && row.answer != '' && !tieneHijos(index)"
              >
                <button class="btn btn-outline-success textoTable" v-if="row.rootCause == false" @click="crearRespuestaNivelInferior(index)">
                  Nueva respuesta de un nivel inferior
                  <i
                    class="fa fa-arrow-right fa-lg"
                    aria-hidden="true"
                  ></i>
                </button>
              </td>
              <td v-else></td>
              <td v-if="row.level == 5">
                <button
                  class="btn btn-outline-success textoTable"
                  v-if="!row.nuevo && row.answer == ''"
                  @click="crearRespuestaMismoNivel(index)"
                >
                  Nueva respuesta del mismo nivel
                  <i class="fa fa-arrow-down fa-lg" aria-hidden="true"></i>
                </button>
                <div v-else-if="!row.nuevo">
                  <p>{{ row.answer }}</p>
                  <div class="form-check" v-if="!tieneHijos(index)">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      disabled
                      v-if="row.rootCause == true"
                    />
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :id="'check' + index"
                      v-model="row.rootCause"
                      @change="marcarCausaRaiz(index)"
                      v-else
                    />
                    <label class="form-check-label textoTable" :for="'check' + index">Es causa raíz</label>
                  </div>
                </div>
                <div v-else>
                  <input
                    type="text"
                    class="form-control pocoMargenBajo textoTable"
                    placeholder="Respuesta"
                    v-model="row.answer"
                  />
                  <button class="btn btn-primary textoTable" @click="confirmarRespuesta(index)">Confirmar</button>
                  <button
                    class="btn btn-outline-danger textoTable"
                    @click="cancelarRespuesta(index)"
                    v-if="rows.length > 1"
                  >Cancelar</button>
                </div>
              </td>
              <td
                v-else-if="row.level == 4 && !row.nuevo && row.answer != '' && !tieneHijos(index)"
              >
                <button class="btn btn-outline-succes textoTables" v-if="row.rootCause == false" @click="crearRespuestaNivelInferior(index)">
                  Nueva respuesta de un nivel inferior
                  <i
                    class="fa fa-arrow-right fa-lg"
                    aria-hidden="true"
                  ></i>
                </button>
              </td>
              <td v-else></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["usuario", "problema", "porques"],

  data() {
    return {
      user: {},
      issue: {},
      whys: [],
      rows: [],
    };
  },

  methods: {
    volverProblemas() {
      window.location.href = "/issues";
    },

    confirmarRespuesta(index) {
      let row = this.$data.rows[index];

      if (row.answer != "") {
        axios
          .post("/fiveWhys/why", row)
          .then((response) => {
            row.id = parseInt(response.data);
            row.nuevaFila = false;
            row.nuevo = false;
            row.rootCause = 0;

            Vue.set(this.$data.rows, index, row);

            let data = {
              level: row.level,
              why_id: row.why_id,
              five_why_id: row.five_why_id,
              nuevo: false,
              nuevaFila: false,
              rootCause: false,
              answer: "",
            };

            this.$data.rows.splice(index + 1, 0, data);
          })
          .catch((error) => {
            console.log(error.response);
          });
      } else {
        Swal.fire("Incorrecto", "La respuesta no puede estar vacía", "warning");
      }
    },

    cancelarRespuesta(index) {
      let row = this.$data.rows[index];

      if (row.nuevaFila == true) {
        this.$data.rows.splice(index, 1);
      } else {
        row.answer = "";
        row.nuevo = false;
        Vue.set(this.$data.rows, index, row);
      }
    },

    crearRespuestaMismoNivel(index) {
      let row = this.$data.rows[index];

      row.nuevo = true;

      Vue.set(this.$data.rows, index, row);
    },

    crearRespuestaNivelInferior(index) {
      let row = this.$data.rows[index];

      let data = {
        level: row.level + 1,
        why_id: row.id,
        five_why_id: row.five_why_id,
        nuevo: true,
        nuevaFila: true,
        rootCause: false,
        answer: "",
      };

      this.$data.rows.splice(index + 1, 0, data);
    },

    marcarCausaRaiz(index) {
      let row = this.$data.rows[index];

      Swal.fire({
        title: "¿Está seguro?",
        text: "Una vez marcado como causa raíz no podrá desmarcarse",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.value) {
          let row = this.$data.rows[index];

          let data = {
            rootCause: row.rootCause,
          };

          axios
            .put("/fiveWhys/changeRootCause/" + row.id, data)
            .then((response) => {
              let data = {
                name: row.answer,
                description: "",
                type: 2,
                state: 3,
                processId: this.$data.issue.process_id,
                issueId: this.$data.issue.id,
                creado: this.$data.user.id,
                modificado: this.$data.user.id
              };

              axios
                .post("/issues", data)
                .then((response) => {
                  let idIssue = parseInt(response.data);

                  axios
                    .get("/users/process/" + data.processId)
                    .then((response) => {
                      let responsables = response.data;

                      responsables.forEach((element) => {
                        let notification = {
                          message:
                            "Se ha creado el problema " +
                            data.name +
                            " por tratarse de una causa raíz",
                          typeElement: 4,
                          idElement: idIssue,
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
            })
            .catch((error) => {
              console.log(error.response);
            });
        } else {
          row.rootCause = false;
          Vue.set(this.$data.rows, index, row);
        }
      });
    },

    tieneHijos(index) {
      let row = this.$data.rows[index];

      let filtrado = this.$data.rows.filter(
        (r) => r.why_id == row.id && r.answer != ""
      );

      return filtrado != null && filtrado.length > 0;
    },
  },

  mounted() {
    this.user = this.usuario;
    this.issue = this.problema;
    this.whys = this.porques;

    let elementos = [...this.whys];
    let primerNivel = [];
    let segundoNivel = [];
    let tercerNivel = [];
    let cuartoNivel = [];
    let quintoNivel = [];

    elementos.forEach((element) => {
      if (element.level == 1) {
        primerNivel.push(element);
      } else if (element.level == 2) {
        segundoNivel.push(element);
      } else if (element.level == 3) {
        tercerNivel.push(element);
      } else if (element.level == 4) {
        cuartoNivel.push(element);
      } else if (element.level == 5) {
        quintoNivel.push(element);
      }
    });

    let caminos = [];

    primerNivel.forEach((element1) => {
      let camino = [];
      element1.nuevo = false;
      camino.push(element1);

      segundoNivel.forEach((element2) => {
        if (element2.why_id == element1.id) {
          element2.nuevo = false;
          camino.push(element2);

          tercerNivel.forEach((element3) => {
            if (element3.why_id == element2.id) {
              element3.nuevo = false;
              camino.push(element3);

              cuartoNivel.forEach((element4) => {
                if (element4.why_id == element3.id) {
                  element4.nuevo = false;
                  camino.push(element4);

                  quintoNivel.forEach((element5) => {
                    if (element5.why_id == element4.id) {
                      element5.nuevo = false;
                      camino.push(element5);

                      // Se filtran de entre todos los del nivel actual, los que tienen el mismo padre
                      // Se coge el ultimo de esta lista y se comprueba si es el actual
                      // En caso de serlo se le añade el elemento nuevo

                      let filtro5 = quintoNivel.filter((r) => {
                        if (r.why_id == element4.id) {
                          return true;
                        } else {
                          return false;
                        }
                      });

                      if (filtro5[filtro5.length - 1].id == element5.id) {
                        let nuevo5 = {
                          nuevo: false,
                          answer: "",
                        };

                        nuevo5.level = 5;
                        nuevo5.why_id = element5.why_id;
                        nuevo5.five_why_id = element5.five_why_id;
                        camino.push(nuevo5);
                      }
                    }
                  });

                  let filtro4 = cuartoNivel.filter((r) => {
                    if (r.why_id == element3.id) {
                      return true;
                    } else {
                      return false;
                    }
                  });

                  if (filtro4[filtro4.length - 1].id == element4.id) {
                    let nuevo4 = {
                      nuevo: false,
                      answer: "",
                    };

                    nuevo4.level = 4;
                    nuevo4.why_id = element4.why_id;
                    nuevo4.five_why_id = element4.five_why_id;
                    camino.push(nuevo4);
                  }
                }
              });

              let filtro3 = tercerNivel.filter((r) => {
                if (r.why_id == element2.id) {
                  return true;
                } else {
                  return false;
                }
              });

              if (filtro3[filtro3.length - 1].id == element3.id) {
                let nuevo3 = {
                  nuevo: false,
                  answer: "",
                };

                nuevo3.level = 3;
                nuevo3.why_id = element3.why_id;
                nuevo3.five_why_id = element3.five_why_id;
                camino.push(nuevo3);
              }
            }
          });

          let filtro2 = segundoNivel.filter((r) => {
            if (r.why_id == element1.id) {
              return true;
            } else {
              return false;
            }
          });

          if (filtro2[filtro2.length - 1].id == element2.id) {
            let nuevo2 = {
              nuevo: false,
              answer: "",
            };

            nuevo2.level = 2;
            nuevo2.why_id = element2.why_id;
            nuevo2.five_why_id = element2.five_why_id;
            camino.push(nuevo2);
          }
        }
      });

      if (primerNivel[primerNivel.length - 1].id == element1.id) {
        let nuevo1 = {
          nuevo: false,
          answer: "",
        };

        nuevo1.level = 1;
        nuevo1.why_id = null;
        nuevo1.five_why_id = element1.five_why_id;
        camino.push(nuevo1);
      }

      caminos.push(camino);
    });

    caminos.forEach((element) => {
      element.forEach((e) => {
        this.rows.push(e);
      });
    });

    let nuevo = {
      nuevo: false,
      answer: "",
    };

    if (this.rows.length == 0) {
      nuevo.level = 1;
      nuevo.nuevo = true;
      nuevo.why_id = null;
      nuevo.five_why_id = this.issue.id;
      nuevo.rootCause = false;
      this.rows.push(nuevo);
    }

    axios.get("/issues/" + this.issue.id + "/fiveWhysBean").then((response) => {
      if (response.data == "") {
        let data = {
          issue_id: this.issue.id,
        };

        axios
          .post("/issues/" + this.issue.id + "/fiveWhys", data)
          .then((response) => {})
          .catch((error) => {
            console.log(error.response);
          });
      }
    });
  },
};
</script>

<style>


</style>
