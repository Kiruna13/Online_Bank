package com.example.banqueenligne

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.Window
import com.google.android.flexbox.FlexboxLayout

class DetailBeneficiaire : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        setContentView(R.layout.activity_detail_beneficiaire)

        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Détails bénéficiaire"
        //set back button
        actionbar.setDisplayHomeAsUpEnabled(true)
        actionbar.setDisplayHomeAsUpEnabled(true)
    }
}