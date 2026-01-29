<div class="comments">
    <ul class="comments__list">
        <li class="comments__item">
            <div class="comments__autor">
                <img class="comments__avatar" src="img/user.svg" alt="">
                <span class="comments__name">John Doe</span>
                <span class="comments__time">30.08.2018, 17:53</span>
            </div>
            <p class="comments__text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
            <div class="comments__actions">
                <div class="comments__rate">
                    <button type="button"><i class="ti ti-thumb-up"></i>12</button>

                    <button type="button">7<i class="ti ti-thumb-down"></i></button>
                </div>

                <button type="button"><i class="ti ti-arrow-forward-up"></i>Reply</button>
                <button type="button"><i class="ti ti-quote"></i>Quote</button>
            </div>
        </li>

        <li class="comments__item comments__item--answer">
            <div class="comments__autor">
                <img class="comments__avatar" src="img/user.svg" alt="">
                <span class="comments__name">John Doe</span>
                <span class="comments__time">24.08.2018, 16:41</span>
            </div>
            <p class="comments__text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            <div class="comments__actions">
                <div class="comments__rate">
                    <button type="button"><i class="ti ti-thumb-up"></i>8</button>

                    <button type="button">3<i class="ti ti-thumb-down"></i></button>
                </div>

                <button type="button"><i class="ti ti-arrow-forward-up"></i>Reply</button>
                <button type="button"><i class="ti ti-quote"></i>Quote</button>
            </div>
        </li>

        <li class="comments__item comments__item--quote">
            <div class="comments__autor">
                <img class="comments__avatar" src="img/user.svg" alt="">
                <span class="comments__name">John Doe</span>
                <span class="comments__time">11.08.2018, 11:11</span>
            </div>
            <p class="comments__text"><span>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or ran domised words which don't look even slightly believable.</span>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <div class="comments__actions">
                <div class="comments__rate">
                    <button type="button"><i class="ti ti-thumb-up"></i>11</button>

                    <button type="button">1<i class="ti ti-thumb-down"></i></button>
                </div>

                <button type="button"><i class="ti ti-arrow-forward-up"></i>Reply</button>
                <button type="button"><i class="ti ti-quote"></i>Quote</button>
            </div>
        </li>
    </ul>

    <form action="#" class="sign__form sign__form--comments">
 <div class="sign__group">
            <input type="text" wire:model.defer="email_name" type="email" class="sign__input" placeholder="">
            @error('email_name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="sign__group">
            <input type="text" wire:model.defer="guest_name" type="name" class="sign__input" placeholder="Name">
            @error('guest_name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="sign__group">
            <textarea id="text" name="text" class="sign__textarea" placeholder="Add comment"></textarea>
        </div>

        <button type="button" class="sign__btn sign__btn--small">Send</button>
    </form>
</div>
