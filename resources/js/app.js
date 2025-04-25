import './bootstrap';


window.Echo.channel('news-notifications')
    .listen('NewsNotificationCreated', (event) => {
        const notification = event.notification;
        
        // Logic to show the notification in the navbar
        const notificationItem = `
            <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"> 
                    <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                        <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xxl"></iconify-icon>
                    </span> 
                    <div>
                        <h6 class="text-md fw-semibold mb-4">${notification.title}</h6>
                        <p class="mb-0 text-sm text-secondary-light text-w-200-px">${notification.message}</p>
                    </div>
                </div>
                <span class="text-sm text-secondary-light flex-shrink-0">Just Now</span>
            </a>
        `;
        
        document.querySelector('.dropdown-menu .max-h-400-px').insertAdjacentHTML('beforeend', notificationItem);
    });
