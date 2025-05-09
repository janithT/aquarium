<template>
  <div>
    <title-bar :title-stack="titleStack"/>
    <hero-bar>
      {{ heroTitle }}
      <router-link slot="right" to="/template/index" class="button">
        Fish template
      </router-link>
    </hero-bar>
    <section class="section is-main-section">
      <tiles>
        <card-component :title="formCardTitle" icon="account-edit" class="tile is-child">
          <form @submit.prevent="submit">
            <template v-if="id">
              <b-field label="ID" horizontal>
                <b-input :value="id" custom-class="is-static" readonly />
              </b-field>
              <hr>
            </template>
            <b-field label="Avatar" horizontal>
              <file-picker @file-id-updated="fileIdUpdated" :current-file="form.avatar_filename"/>
            </b-field>
            <hr>
            <b-field label="Fish name" message="Fish name" horizontal>
              <b-input placeholder="e.g. shark" v-model="form.animal_name" required />
            </b-field>
            <b-field label="Unique identity" message="Unique identity" horizontal>
              <b-input placeholder="e.g. afjafjaakdfaf" v-model="form.unique_identity" required />
            </b-field>
            <b-field label="Status" message="status" horizontal>
              <b-input placeholder="e.g. 0" v-model="form.status" required />
            </b-field>
            <hr>
            <b-field horizontal>
              <b-button type="is-primary" :loading="isLoading" native-type="submit">Submit</b-button>
            </b-field>
          </form>
        </card-component>
        <card-component v-if="isProfileExists" title="Client Profile" icon="account" class="tile is-child">
          <user-avatar :avatar="item.avatar" :is-current-user="false" class="image has-max-width is-aligned-center"/>
          <hr>
          <b-field label="Fish name">
            <b-input :value="item.animal_name" custom-class="is-static" readonly/>
          </b-field>
          <b-field label="Unique">
            <b-input :value="item.unique_identity" custom-class="is-static" readonly/>
          </b-field>
          <b-field label="Status">
            <b-icon 
                :icon="'fish'" 
                size="is-medium" 
                :style="{ color: item.status == 1 ? 'green' : 'red' }"
              />
          </b-field>
          <hr>
        </card-component>
      </tiles>
    </section>
  </div>
</template>

<script>
import clone from 'lodash/clone'
import TitleBar from '@/components/TitleBar'
import HeroBar from '@/components/HeroBar'
import Tiles from '@/components/Tiles'
import CardComponent from '@/components/CardComponent'
import FilePicker from '@/components/FilePicker'
import UserAvatar from '@/components/UserAvatar'
import Notification from '@/components/Notification'

export default {
  name: 'ClientForm',
  components: { UserAvatar, FilePicker, CardComponent, Tiles, HeroBar, TitleBar, Notification },
  props: {
    id: {
      default: null
    }
  },
  data () {
    return {
      isLoading: false,
      item: null,
      form: this.getClearFormObject(),
      createdReadable: null,
    }
  },
  computed: {
    titleStack () {
      let lastCrumb

      if (this.isProfileExists) {
        lastCrumb = this.form.animal_name
      } else {
        lastCrumb = 'New template'
      }

      return [
        'Admin',
        'Template',
        lastCrumb
      ]
    },
    heroTitle () {
      if (this.isProfileExists) {
        return this.form.animal_name
      } else {
        return 'New Fish'
      }
    },
    formCardTitle () {
      if (this.isProfileExists) {
        return 'Edit Fish'
      } else {
        return 'New Fish'
      }
    },
    isProfileExists () {
      return !!this.item
    }
  },
  created () {
    this.getData()
  },
  methods: {
    getClearFormObject () {
      return {
        id: null,
        animal_name: null,
        unique_identity: null,
        status: 0,
        // created_date: new Date(),
        created_mm_dd_yyyy: null,
        avatar: null,
        avatar_filename: null,
        file_id: null
      }
    },
    getData () {
      if (this.id) {
        axios
          .get(`/fish_templates/${this.id}`)
          .then(r => {
            this.form = r.data.data
            this.item = clone(r.data.data)

            this.form.created_date = new Date(r.data.data.created_mm_dd_yyyy)
          })
          .catch(e => {
            this.item = null

            this.$buefy.toast.open({
              message: `Error: ${e.message}`,
              type: 'is-danger',
              queue: false
            })
          })
      }
    },
    fileIdUpdated (fileId) {
      this.form.file_id = fileId
      this.form.avatar_filename = null
    },
    input (v) {
      //this.createdReadable = moment(v).format('MMM D, Y').toString()
    },
    submit () {
      this.isLoading = true
      let method = 'post'
      let url = '/fish_templates/store'

      if (this.id) {
        method = 'patch'
        url = `/fish_templates/${this.id}`
      }

      axios({
        method,
        url,
        data: this.form
      }).then(r => {
        this.isLoading = false

        if (!this.id && r.data.data.id) {
          this.$router.push({name: 'fish_templates.edit', params: {id: r.data.data.id}})

          this.$buefy.snackbar.open({
            message: 'Created',
            queue: false
          })
        } else {
          this.item = r.data.data

          this.$buefy.snackbar.open({
            message: 'Updated',
            queue: false
          })
        }
      }).catch(e => {
        this.isLoading = false

        this.$buefy.toast.open({
          message: `Error: ${e.message}`,
          type: 'is-danger',
          queue: false
        })
      })
    }
  },
  watch: {
    id (newValue) {
      this.form = this.getClearFormObject()
      this.item = null

      if (newValue) {
        this.getData()
      }
    }
  }
}
</script>
