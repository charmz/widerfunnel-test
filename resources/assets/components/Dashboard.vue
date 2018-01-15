<template>
	<div class="columns">
		<div class="column is-half is-offset-one-quarter note-list">
			<div class="box text-center">
				<h2 class="title">Noter - Wider Funnel Test</h2>
				<hr>
					<div class="form-group">
						<input class="input large" type="text" placeholder="New note" v-model="note.body">
					</div>
				    <a class="btn btn-default" @click="createNote()">
					    Add note
					</a>
                    <p>
                        * To edit, double click note text.
                    </p>
				<div class="tabs is-centered">
				</div>
				<div class="card" v-for="note in list">
					<header class="card-header">
						<p class="card-header-title text-left">
						# {{ note.id }}
						</p>
					</header>
					<div class="card-content">
						<div class="content">
							<p v-if="note !== editingNote" @dblclick="editNote(note)" v-bind:title="message">
							{{ note.body }}
							</p>
							<input class="input" v-if="note === editingNote" v-autofocus @keyup.enter="endEditing(note)" @blur="endEditing(note)" type="text" placeholder="New note" v-model="note.body">
						</div>
					</div>
					<footer class="card-footer text-right">
						<a class="card-footer-item btn" v-on:click.prevent="deleteNote(note.id)">Delete</a>
					</footer>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	directives: {
		'autofocus': {
			inserted(el) {
				el.focus();
			}
		}
	},
	data() {
		return {
			message: 'Double click for editing.',
			list: [],
			note: {
				id: '',
				body: '',
				archive: ''
			},
			editingNote: {},
			activeItem: 'current'
		}
	},
	created() {
		this.fetchNoteList();
	},
	methods: {
		fetchNoteList(archive = null) {
			var url = 'api/notes';
			this.setActive('current');
			Vue.http.get(url).then(result => {
				this.list = result.data
			});
		},
		isActive(menuItem) {
			return this.activeItem === menuItem;
		},
		setActive(menuItem) {
			this.activeItem = menuItem;
		},
		createNote() {
			Vue.http.post('api/create_note', this.note).then(result                        => {
				this.note.body = '';
				this.fetchNoteList();
			}).catch(err => {
				console.log(err);
			});
		},
		editNote(note) {
			this.editingNote = note;
		},
		endEditing(note) {
			this.editingNote = {};
			if (note.body.trim() === '') {
				this.deleteNote(note.id);
			} else {
				Vue.http.post('api/edit_note', note).then(result => {
					console.log('access!')
				}).catch(err => {
					console.log(err);
				});
			}
		},
		deleteNote(id) {
			Vue.http.post('api/delete_note/' + id).then(result => {
				this.fetchNoteList();
			}).catch(err => {
				console.log(err);
			});
		}
	}
}
</script>
