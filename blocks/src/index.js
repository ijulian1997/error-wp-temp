import { registerBlockType } from '@wordpress/blocks';

registerBlockType(
	'pg/basic', //nombre del bloque, tiene que ser el mismo en functions.php -> pgRegisterBlock
	{
		title: 'basic block',
		description: 'este es nuestro primer bloque',
		icon: 'smiley',
		category: 'layout',
		edit: () => <h2>Hello world</h2>, // esto es lo del editor
		save: () => <h2>Hello world</h2> // lo que procesa nuestro front
	}
)
