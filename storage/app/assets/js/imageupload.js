function getAttachmentElement(attachment, i) {

	var extension = attachment.name.split('.').pop();
	
	if (['jpg', 'jpeg', 'png', 'svg', 'webp'].includes(extension)) {
		return `<div class="btn btn-outline-secondary attachment img mb-2" style="cursor:default;">
			<img src="${URL.createObjectURL(attachment)}" alt="" class="incident-attachment">
			<span class="incident-attachment-text">
			<i class="fa fa-trash" onclick="delImage(${i})"></i></span>
		</div>`;
	}
	else
	{
		var type = 'txt';
		if (['doc', 'docx'].includes(extension))
			type = 'doc';
		else if (['xls', 'xlsx', 'csv'].includes(extension))
			type = 'xls';
		else if (['rar', 'zip'].includes(extension))
			type = 'zip';
		else if (['ppt', 'pptx'].includes(extension))
			type = 'ppt';
		else if (extension=='pdf')
			type = 'pdf';

		return `<div class="btn btn-outline-secondary attachment mb-2" style="cursor:default;">
				<i class="fa fa-trash" onclick="delImage(${i})" style="z-index:4;"></i>
				<img src="/assets/icons/${type}.svg" alt="" class="p-1" height=42 width=42><br>
				${attachment.name}
				</div>`;


	}

}


let files = [], // will be store images
//button = document.querySelector('.top button'), // uupload button
form = document.querySelector('.attachemnts-card'), // form ( drag area )
container = document.querySelector('.image-container'), // container in which image will be insert
//text = document.querySelector('.inner'), // inner text of form
browse = document.querySelector('.upload-btn'), // text option fto run input
input = document.querySelector('.file'); // file input
//to_upload = document.querySelector('.to-upload'); // file input

browse.addEventListener('click', () => input.click());

// input change event
input.addEventListener('change', () => {
	let file = input.files;

	for (let i = 0; i < file.length; i++) {
		if (files.every(e => e.name !== file[i].name)) files.push(file[i])
	}
	input.value = "";
	showImages();
})


const showImages = () => {
	let images = '';
	const dT = new DataTransfer();
	files.forEach((e, i) => {
		images += getAttachmentElement(e, i);
		dT.items.add(new File([e], e.name));
		input.files = dT.files;
	})
	container.innerHTML = images;
} 

const delImage = index => {
	files.splice(index, 1)
	showImages()
} 

// drag and drop 
/* form.addEventListener('dragover', e => {
	e.preventDefault()

	form.classList.add('dragover')
	//text.innerHTML = 'Drop images here'
})

form.addEventListener('dragleave', e => {
	e.preventDefault()

	form.classList.remove('dragover')
	//text.innerHTML = 'Drag & drop image here or <span class="select">Browse</span>'
})

form.addEventListener('drop', e => {
	e.preventDefault()

    form.classList.remove('dragover')
	//text.innerHTML = 'Drag & drop image here or <span class="select">Browse</span>'

	let file = e.dataTransfer.files;
	for (let i = 0; i < file.length; i++) {
		if (files.every(e => e.name !== file[i].name)) files.push(file[i])
	}

	showImages();
}) */

/* button.addEventListener('click', () => {
	let form = new FormData();
	files.forEach((e, i) => form.append(`file[${i}]`, e))

	// now you can send the image to server
	
}) */
