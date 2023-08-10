class accountEdit {
    constructor() {
        this.switch = document.getElementById('same_address');
        this.delivery = document.getElementById('delivery');
    }

    toggleDelivery(on) {
        let btn = document.getElementById('delivery_btn');
        if(!on) {
            btn.collapse.show();
        }
        else {
            btn.collapse.hide();
        }
    }

    run() {
        let self = this;
        self.switch.addEventListener('change',(e) => { self.toggleDelivery(e.target.checked); });
        self.switch.addEventListener('input',(e) => { self.toggleDelivery(e.target.checked); });
    }
}
const aEdit =  new accountEdit();
window.addEventListener('load', function() {
    aEdit.run();
});