<template>
    <div>
        <div class="form-check form-switch form-check-inline">
            <input  class="form-check-input" type="checkbox" id="studentName" v-model="studentName">
            <label for="studentName" class="form-check-label">Student Name</label>
        </div>
        <div class="form-check form-switch form-check-inline">
            <input  class="form-check-input" type="checkbox" id="email" v-model="email">
            <label for="email" class="form-check-label">Email</label>
        </div>
        <div class="form-check form-switch form-check-inline">
            <input  class="form-check-input" type="checkbox" id="address" v-model="address">
            <label for="address" class="form-check-label">Address</label>
        </div>
        <div class="form-check form-switch form-check-inline">
            <input  class="form-check-input" type="checkbox" id="phoneNo" v-model="phoneNo">
            <label for="phoneNo" class="form-check-label">Phone No</label>
        </div>
        <div class="d-flex justify-content-between align-content-center mb-2">
            <div class="d-flex">
                <div>
                    <div class="d-flex align-items-center ml-4">
                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                            >Per Page</label
                        >
                        <select
                            v-model="paginate"
                            class="form-control form-control-sm"
                        >
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div class="d-flex align-items-center ml-4">
                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                            >FilterBy Class</label
                        >
                        <select
                            v-model="selectedClass"
                            class="form-control form-control-sm"
                        >
                            <option value="">All Class</option>
                            <option
                                v-for="item in classes"
                                :key="item.id"
                                :value="item.id"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div v-if="selectedClass">
                    <div class="d-flex align-items-center ml-4">
                        <label for="paginate" class="text-nowrap mr-2 mb-0"
                            >Section</label
                        >
                        <select
                            v-model="selectedSection"
                            class="form-control form-control-sm"
                        >
                            <option value="">Select a Section</option>
                            <option
                                v-for="section in sections"
                                :key="section.id"
                                :value="section.id"
                            >
                                {{ section.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="dropdown ml-4">
                        <button
                            v-if="checked.length > 0"
                            class="btn btn-secondary dropdown-toggle"
                            data-toggle="dropdown"
                        >
                            With Checked ({{ checked.length }})
                        </button>
                        <div class="dropdown-menu">
                            <a
                                href="#"
                                onclick="confirm('Are you sure you wanna delete this Record?') || event.stopImmediatePropagation()"
                                class="dropdown-item"
                                type="button"
                                @click.prevent="deleteRecords"
                            >
                                Delete
                            </a>

                            <a :href="url" class="dropdown-item" type="button">
                                Export
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <input
                    v-model.lazy="search"
                    type="search"
                    class="form-control"
                    placeholder="Search by name,email,phone,or address..."
                />
            </div>
        </div>

        <div class="col-md-10 mb-2" v-if="selectPage">
            <div v-if="selectAll || students.meta.total == checked.length">
                You are currently selecting all
                <strong>{{ checked.length }}</strong> items.
            </div>
            <div v-else>
                You have selected <strong>{{ checked.length }}</strong> items,
                Do you want to Select All
                <strong>{{ students.meta.total }}</strong
                >?
                <a @click.prevent="selectAllRecords" href="#" class="ml-2"
                    >Select All</a
                >
            </div>
        </div>

        <div class="card-body table-responsive p-0">

            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th><input type="checkbox" v-model="selectPage" /></th>
                        <th v-if="studentName">
                            <a href="#" @click.prevent="change_sort('name')"
                                >Student's Name</a
                            >
                            <span
                                v-if="
                                    sort_direction == 'desc' &&
                                        sort_field == 'name'
                                "
                                >&uarr;</span
                            >
                            <span
                                v-if="
                                    sort_direction == 'asc' &&
                                        sort_field == 'name'
                                "
                                >&darr;</span
                            >
                        </th>

                        <th v-if="email">
                            <a href="#" @click.prevent="change_sort('email')"
                                >Email</a
                            >
                            <span
                                v-if="
                                    sort_direction == 'desc' &&
                                        sort_field == 'email'
                                "
                                >&uarr;</span
                            >
                            <span
                                v-if="
                                    sort_direction == 'asc' &&
                                        sort_field == 'email'
                                "
                                >&darr;</span
                            >
                        </th>
                        <th v-if="address">
                            <a href="#" @click.prevent="change_sort('address')"
                                >Address</a
                            >
                            <span
                                v-if="
                                    sort_direction == 'desc' &&
                                        sort_field == 'address'
                                "
                                >&uarr;</span
                            >
                            <span
                                v-if="
                                    sort_direction == 'asc' &&
                                        sort_field == 'address'
                                "
                                >&darr;</span
                            >
                        </th>
                        <th v-if="phoneNo">
                            <a
                                href="#"
                                @click.prevent="change_sort('phone_number')"
                                >Phone Number</a
                            >
                            <span
                                v-if="
                                    sort_direction == 'desc' &&
                                        sort_field == 'phone_number'
                                "
                                >&uarr;</span
                            >
                            <span
                                v-if="
                                    sort_direction == 'asc' &&
                                        sort_field == 'phone_number'
                                "
                                >&darr;</span
                            >
                        </th>
                        <th>
                            <a
                                href="#"
                                @click.prevent="change_sort('created_at')"
                                >Created At</a
                            >
                            <span
                                v-if="
                                    sort_direction == 'desc' &&
                                        sort_field == 'created_at'
                                "
                                >&uarr;</span
                            >
                            <span
                                v-if="
                                    sort_direction == 'asc' &&
                                        sort_field == 'created_at'
                                "
                                >&darr;</span
                            >
                        </th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th v-if="studentName">
                            <input @keyup.enter="getStudents()" type="text" class="form-input w-100"  v-model="params.name">
                        </th>
                        <th v-if="email">
                            <input @keyup.enter="getStudents()" type="text" class="form-input w-100"  v-model="params.email">
                        </th>
                        <th v-if="address">
                            <input @keyup.enter="getStudents()" type="test" class="form-input w-100"  v-model="params.address">
                        </th>
                        <th v-if="phoneNo">
                            <input @keyup.enter="getStudents()" type="test" class="form-input w-100"  v-model="params.phoneNo">
                        </th>
                    </tr>

                    <tr
                        v-for="student in students.data"
                        :key="student.id"
                        :class="isChecked(student.id) ? 'table-primary' : ''"
                    >
                        <td>
                            <input
                                type="checkbox"
                                :value="student.id"
                                v-model="checked"
                            />
                        </td>
                        <td v-if="studentName">{{ student.name }}</td>
                        <td v-if="email">{{ student.email }}</td>
                        <td v-if="address">{{ student.address }}</td>
                        <td v-if="phoneNo">{{ student.phone_number }}</td>
                        <td>{{ student.created_at }}</td>
                        <td>{{ student.class }}</td>
                        <td>{{ student.section }}</td>
                        <td>
                            <button
                                onclick="confirm('Are you sure you wanna delete this Record?') || event.stopImmediatePropagation()"
                                class="btn btn-danger btn-sm"
                                @click="deleteSingleRecord(student.id)"
                            >
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6 offset-5">
                <pagination
                    :data="students"
                    @pagination-change-page="getStudents"
                ></pagination>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            students: {},
            paginate: 10,
            search: "",
            classes: {},
            selectedClass: "",
            selectedSection: "",
            sections: {},
            checked: [],
            selectPage: false,
            selectAll: false,
            sort_direction: "desc",
            sort_field: "created_at",
            url: "",
            getStudentsUrl: "",
            getStudentsUrlWithoutPaginate: "",
            studentName:true,
            email:true,
            address:true,
            phoneNo:true,
            // searchPhone:"",
            params:{
                name:'',
                email:'',
                address:'',
                phoneNo:''

            }

        };
    },

    watch: {
        paginate: function(value) {
            this.getStudents();
        },
        search: function(value) {
            this.getStudents();
        },
        params: function(value) {
            this.getStudents();
        },

       selectedClass: function(value) {
            this.selectedSection = "";
            axios
                .get("/api/sections?class_id=" + this.selectedClass)
                .then(response => {
                    this.sections = response.data.data;
                });
            this.getStudents();
        },
        selectedSection: function(value) {
            this.getStudents();
        },
        selectPage: function(value) {
            this.checked = [];
            if (value) {
                this.students.data.forEach(student => {
                    this.checked.push(student.id);
                });
            } else {
                this.checked = [];
                this.selectAll = false;
            }
        },
        checked: function(value) {
            this.url = "/api/students/export/" + this.checked;
        }
    },

    methods: {
        selectAllRecords() {
            axios.get(this.getStudentsUrlWithoutPaginate).then(response => {
                // console.log(response.data);
                this.checked = [];
                response.data.data.forEach(student => {
                    this.checked.push(student.id);
                });
                this.selectAll = true;
            });
        },
        change_sort(field) {
            if (this.sort_field == field) {
                this.sort_direction =
                    this.sort_direction == "asc" ? "desc" : "asc";
            } else {
                this.sort_field = field;
            }
            this.getStudents();
        },
        deleteSingleRecord(student_id) {
            axios.delete("/api/student/delete/" + student_id).then(response => {
                this.checked = this.checked.filter(id => id != student_id);
                this.$toast.success("Student Deleted Successfully");
                this.getStudents();
            });
        },
        deleteRecords() {
            axios
                .delete("/api/students/massDestroy/" + this.checked)
                .then(response => {
                    if (response.status == 204) {
                        this.$toast.success(
                            "Selected Students were Deleted Successfully"
                        );
                        this.checked = [];
                        this.getStudents();
                    }
                });
        },
        isChecked(student_id) {
            return this.checked.includes(student_id);
        },
        getResults() {
            axios.get('/api/students', { params: { keyword: this.keyword } })
                .then(res => this.students = res.data)
                .catch(error => {});
        },
        getStudents(page = 1) {
            this.getStudentsUrlWithoutPaginate =
                "/api/students?" +
                "q=" +
                this.search +
                "&sort_direction=" +
                this.sort_direction +
                "&sort_field=" +
                this.sort_field +
                "&selectedClass=" +
                this.selectedClass +
                "&selectedSection=" +
                this.selectedSection;

            this.getStudentsUrl = this.getStudentsUrlWithoutPaginate.concat(
                "&paginate=" + this.paginate + "&page=" + page
            );
            axios.get(this.getStudentsUrl,
            {
                headers:{
                    name: this.params.name,
                    email: this.params.email,
                    address: this.params.address,
                    phoneNo: this.params.phoneNo,
                }
              }
            ).then(response => {
                this.students = response.data;
            });
        }
    },

    mounted() {
        axios.get("/api/classes").then(response => {
            this.classes = response.data.data;
        });
        this.getStudents();
    }
};
</script>
