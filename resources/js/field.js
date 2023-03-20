import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-translatable-slug', IndexField)
  app.component('detail-translatable-slug', DetailField)
  app.component('form-translatable-slug', FormField)
})
