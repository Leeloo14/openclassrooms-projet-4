<template>
    <div>
        <h1>Vos billets</h1>
        <hr/>
        <div>
            <div class="form-group">
                <label>Nombre de places:</label>
                <div>
                    <input class="form-control" type="number" v-model="reservation.nbPlaces" @change="onDataChange"/>
                </div>
            </div>
            <div class="form-group">
                <label>Date:</label>
                <div class="d-flex align-items-center">
                    <datepicker
                        input-class="form-control"
                        :format="customFormatter"
                        :disabled-dates="disabledDates"
                        @selected="onVisitDateChange"
                    ></datepicker>
                </div>
            </div>
            <div class="form-group">
                <label>Demi-journée:</label>
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-control" v-model="reservation.isHalf"/>
                </div>
            </div>
            <button class="btn btn-primary mr-3"
                    @click="clickNextAction()"
                    v-bind:class="{disabled: !reservation.isDateAvailable}">Suivant
            </button>
            <i class="fas fa-spinner fa-spin" v-if="isCheckingAvailability"></i>
            <div class="alert alert-danger mt-2" v-if="showImpossibleAlert">
                Il n'est pas possible de réserver une entrée sur cette date.
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Datepicker from 'vuejs-datepicker';
    import dayjs from 'dayjs'

    export default {
        name: 'Step1',
        components: {Datepicker},
        props: {
            reservation: Object,
            isCheckingAvailability: Boolean,
            setIsDateAvailable: Function,
            setNbBillets: Function,
            navigateAction: Function,
            onDataChange: Function,
            onVisitDateChange: Function,
        },
        data() {
          return {
           disabledDates: {
             customPredictor(date) {
               const excludedDatesConditions = dayjs(date).day() === 2 || dayjs(date).day() === 0 ||
                 dayjs(date).format('DDMM') === '0105' ||
                 dayjs(date).format('DDMM') === '1111' ||
                 dayjs(date).format('DDMM') === '2512';
               return dayjs(date).isBefore(dayjs().subtract(1, 'day')) || excludedDatesConditions
             }
           }
          }
        },
        computed: {
            showImpossibleAlert() {
                const {isDateAvailable, visitDate} = this.reservation
                return isDateAvailable !== null && !isDateAvailable && visitDate
            }
        },
        methods: {
            clickNextAction() {
                this.setNbBillets();
                this.navigateAction('Step2')
            },
            customFormatter(date) {
                return dayjs(date).format('DD/MM/YYYY');
            }
        }
    }
</script>

<style lang="scss"></style>
