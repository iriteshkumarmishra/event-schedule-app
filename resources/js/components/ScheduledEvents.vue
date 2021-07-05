<template>
    <div class="schedule-events container mt-4">
        <!--Page Header -->
        <div class="mb-4 d-md-flex align-items-md-center text-md-left">
            <nav aria-label="breadcrumb">
                <h1 class="h3">Events</h1>
                <ol class="breadcrumb bg-transparent small p-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Events</li>
                </ol>
            </nav>
            <div class="ml-auto">
                <el-button type="primary ml-auto" @click="showAddEventDialog"><i class="el-icon-plus"></i>Add Event</el-button>
            </div>
        </div>
        <!-- End Page Header -->
        <el-calendar v-model="currentCalenderValue" size="small">
            <template
                slot="dateCell"
                slot-scope="{date, data}">
                <div class="calendar-day-box">
                    <div class="calendar-day">{{ data.day.split('-').slice(2).join('-') }}</div>
                    <div v-for="item in calendarData" v-bind:key="item.id">
                        <div v-if="(item.months).indexOf(data.day.split('-').slice(1)[0])!=-1">
                            <div v-if="(item.days).indexOf(data.day.split('-').slice(2).join('-'))!=-1">
                                <el-tooltip class="item" effect="dark" :content="item.things" placement="right">
                                    <div class="is-selected">{{item.name.length > 15 ? item.name.substring(0,15)+ '..' : item.name}}</div>
                                </el-tooltip>
                            </div>
                            <div v-else></div>
                        </div>
                        <div v-else></div>
                    </div>
                </div>
            </template>
        </el-calendar>
        <el-dialog title="Create an Event" :visible.sync="addEventDialog" v-loading='eventForm.doingAjax' :before-close="handleClose" :show-close="false">
            <el-form :model="eventForm.form" ref="eventForm" size="small">
                <el-form-item label="Name" prop="name">
                    <el-input v-model="eventForm.data.name"></el-input>
                    <small class="text-danger" v-if="eventForm.errors.name">{{ eventForm.errors.name[0]}}</small>
                </el-form-item>
                <el-form-item label="Description" prop="description">
                    <el-input v-model="eventForm.data.description"></el-input>
                    <small class="text-danger" v-if="eventForm.errors.description">{{ eventForm.errors.description[0]}}</small>
                </el-form-item>
                <el-row :gutter="24">
                    <el-col :md="12" :lg="12" :xl="12">
                        <el-form-item label="Start time" prop="startTime">
                            <el-time-select class="w-100" placeholder="Start time" v-model="eventForm.data.startTime" :picker-options="{ start: '00:00',  step: '00:15',  end: '23:45'}">
                            </el-time-select>
                        </el-form-item>
                    </el-col>
                    <el-col :md="12" :lg="12" :xl="12">
                        <el-form-item label="End time" prop="endTime">
                            <el-time-select class="w-100" placeholder="Start time" v-model="eventForm.data.endTime" :picker-options="{ start: '00:00',  step: '00:15',  end: '23:45', minTime: eventForm.data.startTime }">
                            </el-time-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="Day of the week">
                    <el-select class="w-100" v-model="eventForm.data.dayOfTheWeek" prop="dayOfTheWeek" filterable multiple>
                        <el-option v-for="day in weekDays" :key="day.id" :label="day.display_name" :value="day.id">
                        </el-option>
                    </el-select>
                    <small class="text-danger" v-if="eventForm.errors.role">{{eventForm.errors.role[0]}}</small>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer text-center mb-2">
                <el-button @click="closeEventFormDialog()" type="primary" plain class="mr-4"><i class="el-icon-close mr-2"></i>Cancel</el-button>
                <el-button type="primary" @click="submitAddEventForm('eventForm')"><i class="el-icon-check mr-2"></i>Create Event</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<style scoped>
    .calendar-day{
        color: #202535;
        font-size: 12px;
    }
    .is-selected{
        color: #1989FA;
        font-size: 11px;
    }
    #calendar .el-button-group>.el-button:not(:first-child):not(:last-child):after{
        content: 'Current month';
    }
</style>

<script>
export default {
    data() {
        return {
            calendarData: '',
            currentCalenderValue: '',
            blockUI: false,
            addEventDialog: false,
            eventForm: {
                doingAjax: false,
                data: {
                    name: '',
                    desciprtion: '',
                    startTime: '',
                    endTime: '',
                    dayOfTheWeek: [],
                },
                errors : [],
                rules : {
                    name: [{ required: true, message: 'The name field is required.', trigger: 'blur' }],
                    desciprtion: [{ required: true, message: 'The description field is required.', trigger: 'blur' }],
                    startTime: [{ required: true, message: 'The time field is required.', trigger: 'blur' }],
                    endTime: [{ required: true, message: 'The time field is required.', trigger: 'blur' }],
                    dayOfTheWeek: [{ required: true, message: 'The day field is required.', trigger: 'blur' }],
                },
            },
            weekDays: [
                {"id": 0, "display_name": "Sunday"},
                {"id": 1, "display_name": "Monday"},
                {"id": 2, "display_name": "Tuesday"},
                {"id": 3, "display_name": "Wednesday"},
                {"id": 4, "display_name": "Thursday"},
                {"id": 5, "display_name": "Friday"},
                {"id": 6, "display_name": "Saturday"},
            ],
        }
    },
    created() {
        let vm = this;
        vm.blockUI = true;
        axios.get('/api/event-schedules').then(function(response) {
            vm.calendarData = response.data;
            vm.blockUI = false;
        });
    },
    methods: {
        showAddEventDialog() {
            let vm = this;
            vm.addEventDialog = true;
        },
        submitAddEventForm(form) {
            let vm = this;
            vm.eventForm.doingAjax = true;
            vm.eventForm.errors = [];        // just to clear the error box from screen when everything is fine
            axios.post("/api/event/create", vm.eventForm.data).then(function(response) {
                vm.$notify({ type: "success", title: "Success", message: response.data.message, duration: 1000,
                    onClose: function() { location.reload(); }
                });
            })
            .catch(function(error) {
                console.log(error.response.data.message);
                vm.eventForm.errors = error.response.data.errors;
                vm.eventForm.doingAjax = false;
                vm.$notify({ type: "error", title: 'Whoops!', message: 'Please correct the error mentioned against each field and try again', duration: 2000});
                window.scrollTo({top: 0, left: 0, behavior: 'smooth'});
            });
        },
        handleClose() {
            return false;
        },
        closeEventFormDialog() {
            let vm = this;
            vm.resetForm('eventForm');
            vm.addEventDialog = false;
        },
        resetForm(form) {
            let vm = this;
            vm.$refs[form].resetFields();
            vm.eventForm.data = [];
            vm.eventForm.errors = [];
        },
    }
}
</script>