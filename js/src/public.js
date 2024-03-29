class accountMenu {
    constructor() {
        this.menu = document.getElementById('user-panel');
        this.panels = document.querySelectorAll('.panels');
        this.panelBackBtn = document.querySelectorAll('.panelBack');
        this.tosigninBtn = document.querySelectorAll('.tosignin');
        this.topwdBtn = document.querySelectorAll('.topwd');
    }

    updatePanelsClasses(type,className) {
        this.panels.forEach(function(p){
            if(typeof className === "string") p.classList[type](className);
            else if(typeof className === "object" && className.length > 0) p.classList[type](...className);
        });
    }

    run() {
        let self = this;
        self.panelBackBtn.forEach(function(btn){
            btn.addEventListener('click',function(e){
                e.preventDefault();
                self.updatePanelsClasses('remove',['signin-active','pwd-active']);
                return false;
            });
        });
        self.tosigninBtn.forEach(function(btn){
            btn.addEventListener('click',function(e){
                e.preventDefault();
                self.updatePanelsClasses('remove','pwd-active');
                self.updatePanelsClasses('add','signin-active');
                return false;
            });
        });
        self.topwdBtn.forEach(function(btn){
            btn.addEventListener('click',function(e){
                e.preventDefault();
                self.updatePanelsClasses('remove','signin-active');
                self.updatePanelsClasses('add','pwd-active');
                return false;
            });
        });

        if (typeof IScroll !== "undefined") {
            let menu = self.menu.classList.contains('logged') ? '#user-panel' : '.signin-panel';
            let scrollmenu = new IScroll(menu, {
                mouseWheel: true,
                scrollbars: false
            });
            let resize_ob = new ResizeObserver(() => scrollmenu.refresh() );
            let mutation_ob = new MutationObserver(() => scrollmenu.refresh() );

            self.menu.addEventListener('shown.bs.collapse', () => {
                resize_ob.observe(document.querySelector(menu))
                mutation_ob.observe(document.querySelector(menu),{ childList:true, subtree:true })
            });
            self.menu.addEventListener('hidden.bs.collapse', () => {
                resize_ob.unobserve(document.querySelector(menu))
                mutation_ob.disconnect()
            });
        }
    }
}
const account =  new accountMenu();
window.addEventListener('load', function() {
    account.run();
});