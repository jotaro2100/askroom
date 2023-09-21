'use strict';

{
    // フラッシュアニメーション
    function flashClose() {
        const flash = document.querySelector(`#flash`);

        if (flash) {
            setTimeout(function () {
                const animation = flash.animate(
                    [
                        { transform: 'translateY(0)' },
                        { transform: 'translateY(-40px)' }
                    ],
                    {
                        duration: 1000,
                        iterations: 1
                    }
                );

                animation.onfinish = function () {
                    flash.style.display = 'none';
                };
            }, 3000);
        }
    };

    flashClose();
}
