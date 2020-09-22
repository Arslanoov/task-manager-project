import { mount, createLocalVue } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import Alert from './../../src/components/Alert.vue'

describe('Alert', () => {
    it('displays empty default message', () => {
        let wrapper = mount(Alert);
        expect(wrapper.text()).toContain('');
    });

    it('show error message', () => {
        const localVue = createLocalVue();
        localVue.use(BootstrapVue);

        let wrapper = mount(Alert, {
            localVue,
            propsData: {
                error: 'Some error message'
            }
        });

        expect(wrapper.find('.error-alert').text()).toContain('Some error message');
    });
});
