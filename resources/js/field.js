import IndexField from './components/IndexField';
import FormField from './components/FormField';
import DetailField from './components/DetailField';

Nova.booting((Vue, router, store) => {
  Vue.component('index-translatable-slug', IndexField);
  Vue.component('form-translatable-slug', FormField);
  Vue.component('detail-translatable-slug', DetailField);
});
