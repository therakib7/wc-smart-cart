export interface Form {
	layout: string;
	position: string;
	close_after: number;
	display_condition: string[];
}

export interface State {
	loading: boolean;
	saving: boolean;
	form: Form;
}
